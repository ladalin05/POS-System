<?php

namespace App\Http\Controllers\Api\V1;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Endroid\QrCode\QrCode;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use Endroid\QrCode\Logo\Logo;
use App\Models\Api\V1\Payment;
use Endroid\QrCode\Color\Color;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Http;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\ErrorCorrectionLevel;
use App\Http\Requests\Api\V1\Payment\CreatePaymentRequest;

class PaymentController extends Controller
{
    use ApiResponses;

    public function webView(Request $request)
    {
        $request->validate([
            'token' => 'required',
        ]);
        // $payload = [
        //     'amount' => 10,
        //     'name' => 'John Doe',
        //     'image' => 'https://via.placeholder.com/150',
        //     'iat' => time(),
        //     'nbf' => config('sanctum.expiration'),
        // ];
        // $token = JWT::encode($payload, env('SANCTUM_STATEFUL_SECRET'), 'HS256');
        // dd($token);
        $data = JWT::decode($request->token, new Key(env('SANCTUM_STATEFUL_SECRET'), 'HS256'));
        return view('api.webview', compact('data'));
    }
    public function makeHash(Request $request)
    {
        $str = $request->tran_id . $request->req_time . $request->amount . $request->return_url . $request->continue_success_url;
        $secret = 'QhSNJNIjvVKHmEoSCX5qpz0AG1pzkyGP3jgzIC9r99UU5Xtadq2oaO1zhRrSsAb0pMPWX';
        $hash = partnerHash($str, $secret);
        return $this->ok('Hash created', ['hash' => $hash]);
    }
    public function index(Request $request)
    {
        if ($request->status === 0) {
            $data = json_encode($request->all());
            $this->verifyPayment($request->tran_id, $data);
            sentTelegram("{$request->tran_id}: ABA Callback: Payment Success Data: {$data}");
        }
        return $this->ok('Payment Success');
    }
    public function createPayment(CreatePaymentRequest $request)
    {
        $request->validated($request->all());
        $tran_id = $request->tran_id;
        $req_time = $request->req_time;
        $payment_gate = 0;
        $payment_option = 'abapay_khqr_deeplink';
        $return_url = $request->return_url;
        $continue_success_url = $request->continue_success_url;
        $partner_hash = $request->hash;
        $amount = $request->amount;
        $secret = 'QhSNJNIjvVKHmEoSCX5qpz0AG1pzkyGP3jgzIC9r99UU5Xtadq2oaO1zhRrSsAb0pMPWX';
        // if (!partnerHashVerify($tran_id . $req_time . $amount . $return_url . $continue_success_url, $secret, $partner_hash)) {
        //     return $this->error('Invalid hash', 400);
        // }
        $req_time = time();
        $tran_id = time();
        $amount = number_format($amount, 2, '.', '');
        $merchant_id = env('ABA_PAYWAY_MERCHANT_ID');
        $firstName = '';
        $lastName = '';
        $email = '';
        $phone = '';
        $return_url = 'https://payment-gateway-staging.wintech.com.kh/callback';
        $hash = abaHash($req_time . $merchant_id . $tran_id . $amount . $firstName . $lastName . $email . $phone . $payment_option . $return_url . $continue_success_url);
        $req = [
            'req_time' => $req_time,
            'merchant_id' => $merchant_id,
            'tran_id' => $tran_id,
            'amount' => $amount,
            'hash' => $hash,
            'payment_gate' => $payment_gate,
            'payment_option' => $payment_option,
            'view_type' => 'hosted_view',
        ];
        if (!empty($firstName)) {
            $req['first_name'] = $firstName;
        }
        if (!empty($lastName)) {
            $req['lastname'] = $lastName;
        }
        if (!empty($email)) {
            $req['email'] = $email;
        }
        if (!empty($phone)) {
            $req['phone'] = $phone;
        }
        if (!empty($return_url)) {
            $req['return_url'] = $return_url;
        }
        if (!empty($continue_success_url)) {
            $req['continue_success_url'] = $continue_success_url;
        }
        $res = Http::withHeaders([
            'referer' => env('ABA_REQUEST_HOST'),
            'origin' => env('ABA_REQUEST_HOST'),
        ])->post(abaAction(), $req);
        $http_code = $res->status();
        Payment::create([
            'tran_id' => $tran_id,
            'amount' => $amount,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'phone' => $phone,
            'email' => $email,
            'status' => 'pending',
            'req' => json_encode($req),
            'res' => $res->body(),
            'partner_hash' => $partner_hash,
        ]);
        if ($http_code == 200) {
            $resObject = $res->object();
            if (isset($resObject->status) && $resObject->status->code == '00') {
                $qr = $this->createQrCode($resObject->qr_string, 'uploads/sntunlocker', $tran_id);
                $payload = [
                    'tran_id' => $tran_id,
                    'amount' => $amount,
                    'name' => 'WINTECHSOFTWARE',
                    'image' => $qr,
                    'iat' => time(),
                    'nbf' => config('sanctum.expiration'),
                ];
                $token = JWT::encode($payload, env('SANCTUM_STATEFUL_SECRET'), 'HS256');
                    $data = [
                        'tran_id' => $tran_id,
                        'status' => 'created',
                        'qr_string' => $resObject->qr_string,
                        'payment_link' => url('api/v1/payment/webview?token=' . $token),
                    ];
                return $this->ok('Payment created', $data);
            }
        }
        return $this->error('Error creating payment');
    }
    // check payment status
    public function checkPaymentStatus(Request $request)
    {
        $tran_id = $request->tran_id;
        $res = $this->verifyPayment($tran_id);
        return $this->ok('Payment status', $res);
    }
    // verify payment
    private function createQrCode($text, $path, $name)
    {
        $writer = new PngWriter();
        $qrCode = new QrCode(
            data: $text,
            size: 300,
            errorCorrectionLevel: ErrorCorrectionLevel::Low,
            encoding: new Encoding('UTF-8'),
            margin: 0,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
            foregroundColor: new Color(0, 0, 0),
            backgroundColor: new Color(255, 255, 255),
        );
        $logo = new Logo(
            path: public_path('assets/images/icons/dollar.png'),
            resizeToWidth: 50,
            punchoutBackground: true
        );
        if(!is_dir(public_path($path))) {
            mkdir(public_path($path), 0777, true);
        }
        $result = $writer->write($qrCode, $logo);
        $result->saveToFile(public_path("$path/$name.png"));
        if (file_exists(public_path("$path/$name.png"))) {
            return asset("$path/$name.png?v=" . time());
        }
        return '';
    }
    private function verifyPayment($tran_id, $data = '')
    {
        $payment = Payment::where('tran_id', $tran_id)->first();
        if (!$payment) {
            return [
                'status' => 'error',
                'message' => 'Payment not found',
            ];
        }
        if ($payment->status === 'completed') {
            return [
                'status' => 'completed',
                'message' => 'Payment is completed',
                'tran_id' => $payment->tran_id,
                'amount' => (float)number_format($payment->amount, 2, '.', ''),
            ];
        }
        $tran_id = $payment->tran_id;
        $req_time = time();
        $merchant_id = env('ABA_PAYWAY_MERCHANT_ID');
        $hash = abaHash($req_time . $merchant_id . $tran_id);

        $req = [
            'req_time' => $req_time,
            'merchant_id' => $merchant_id,
            'tran_id' => $tran_id,
            'hash' => $hash,
        ];
        $res = Http::withHeaders([
            'referer' => env('ABA_REQUEST_HOST'),
            'origin' => env('ABA_REQUEST_HOST'),
        ])->post('https://checkout-sandbox.payway.com.kh/api/payment-gateway/v1/payments/check-transaction', $req);

        $http_code = $res->status();
        if ($http_code == 200) {
            $resObject = $res->object();
            if (isset($resObject->status) && $resObject->status === 2) {
                return [
                    'status' => 'pending',
                    'message' => 'Payment is pending',
                    'tran_id' => $payment->tran_id,
                    'amount' => (float)number_format($payment->amount, 2, '.', ''),
                ];
            }
            if (isset($resObject->status) && $resObject->status === 0) {
                $payment->status = 'completed';
                sentTelegram("{$payment->tran_id}: Update Payment Status: Completed");
                $this->callbackpay($data);
                return [
                    'status' => 'completed',
                    'message' => 'Payment is completed',
                    'tran_id' => $payment->tran_id,
                    'amount' => (float)number_format($payment->amount, 2, '.', ''),
                ];
            }
            $payment->callback = $res->body();
            $payment->save();
        }
        return [
            'status' => 'error',
            'message' => 'Error checking payment status',
        ];
    }
    private function callbackpay($req)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://sntunlock.com/modules/gateways/callback/wintech.php',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_POSTFIELDS => $req,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        echo $response;
    }
}
