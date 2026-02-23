<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Other\CashAccount;
use App\Models\Other\Currencies;
use App\Models\Sales\Payment;
use App\Models\Sales\Sales;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Pest\Support\Str;
use Illuminate\Validation\Rule;


class PaymentController extends Controller
{
    public function save(Request $request, $id = null)
    {
        try {
            if (!$request->isMethod('post')) {
                abort(405);
            }

            $rules = [
                'date' => 'required|date',
                'reference_no' => 'nullable|string|max:100',
                'amount' => 'required',
                'discount' => 'nullable',
                'amount_usd' => 'nullable',
                'rate_usd' => 'nullable',
                'amount_khr' => 'nullable',
                'rate_khr' => 'nullable',
                'paying_by' => 'required|string|max:50',
                'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
                'note' => 'nullable|string',
                'sale_id' => 'nullable|integer|exists:sales,id',
                'allow_overpayment' => 'nullable|boolean',
            ];

            $messages = [];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors(),
                ], 422);
            }

            // Handle file upload (attachment) if present
            $attachmentFilename = null;
            if ($request->hasFile('attachment') && $request->file('attachment')->isValid()) {
                $file = $request->file('attachment');

                $attachmentFilename = time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/payments', $attachmentFilename);
            }

            $whenLocal = $request->filled('date')
                ? Carbon::parse($request->input('date'), 'Asia/Phnom_Penh')
                : now('Asia/Phnom_Penh');
            $whenUtc = $whenLocal->copy()->utc();

            $referenceNo = $request->filled('reference_no')
                ? $request->reference_no
                : $this->nextReference($whenLocal);


            $data = [
                'date' => $request->input('date'),
                'reference_no' => $referenceNo,
                'amount' => $request->filled('amount') ? (float) $request->input('amount') : 0,
                'discount' => $request->filled('discount') ? (float) $request->input('discount') : 0,
                'amount_usd' => $request->filled('amount_usd') ? (float) $request->input('amount_usd') : null,
                'rate_usd' => $request->filled('rate_usd') ? (float) $request->input('rate_usd') : null,
                'amount_khr' => $request->filled('amount_khr') ? (float) $request->input('amount_khr') : null,
                'rate_khr' => $request->filled('rate_khr') ? (float) $request->input('rate_khr') : null,
                'paying_by' => $request->input('paying_by'),
                'note' => $request->input('note'),
                'sale_id' => $request->input('sale_id') ?: null,
                'allow_overpayment' => $request->has('allow_overpayment') ? (bool) $request->input('allow_overpayment') : false,
            ];

            if ($attachmentFilename) {
                $data['attachment'] = $attachmentFilename;
            }

            // Wrap create/update + sale recalculation in a transaction
            $payment = null;
            DB::transaction(function () use ($data, $id, &$payment) {
                // create or update payment
                $payment = Payment::updateOrCreate(['id' => $id], $data);

                // If payment is linked to a sale, recalc sale totals
                if ($payment->sale_id) {
                    $sale = Sales::find($payment->sale_id);

                    if ($sale) {
                        // Determine sale currency (assumption: sale->currency exists and grand_total is in that currency)
                        $saleCurrency = strtoupper($sale->currency ?? 'USD'); // default to USD if not set

                        // Load all payments for this sale
                        $payments = Payment::where('sale_id', $sale->id)->get();

                        $totalPaid = 0.0;
                        $totalDiscount = 0.0;

                        foreach ($payments as $p) {
                            //
                            // --- Convert payment value to USD (best-effort) ---
                            //
                            $paymentUsd = 0.0;
                            if (!is_null($p->amount_usd)) {
                                // amount_usd explicitly provided => assume USD
                                $paymentUsd = (float) $p->amount_usd;
                            } elseif (!is_null($p->amount_khr) && !is_null($p->rate_khr) && (float) $p->rate_khr > 0) {
                                // amount_khr with rate_khr (KHR per 1 USD) => USD = amount_khr / rate_khr
                                $paymentUsd = (float) $p->amount_khr / (float) $p->rate_khr;
                            } elseif (!is_null($p->amount) && !is_null($p->rate_usd) && (float) $p->rate_usd > 0) {
                                // fallback using amount and rate_usd interpretation (adjust if your rate_usd meaning differs)
                                $paymentUsd = (float) $p->amount * (float) $p->rate_usd;
                            } else {
                                // final fallback: use amount as-is
                                $paymentUsd = !is_null($p->amount) ? (float) $p->amount : 0.0;
                            }

                            //
                            // --- Convert discount to USD (best-effort) ---
                            // We assume the payment->discount uses same currency origin as payment amounts:
                            //
                            $discountUsd = 0.0;
                            if (!is_null($p->amount_usd)) {
                                // if amount_usd exists, assume discount is in USD scale
                                $discountUsd = !is_null($p->discount) ? (float) $p->discount : 0.0;
                            } elseif (!is_null($p->amount_khr) && !is_null($p->rate_khr) && (float) $p->rate_khr > 0) {
                                // discount in KHR -> convert to USD
                                $discountUsd = !is_null($p->discount) ? ((float) $p->discount / (float) $p->rate_khr) : 0.0;
                            } elseif (!is_null($p->discount) && !is_null($p->rate_usd) && (float) $p->rate_usd > 0) {
                                // fallback: interpret discount * rate_usd (adjust if rate_usd meaning differs)
                                $discountUsd = (float) $p->discount * (float) $p->rate_usd;
                            } else {
                                // last fallback: use discount as-is
                                $discountUsd = !is_null($p->discount) ? (float) $p->discount : 0.0;
                            }

                            //
                            // --- Convert USD amounts into sale currency ---
                            //
                            $paymentInSaleCurrency = 0.0;
                            $discountInSaleCurrency = 0.0;

                            if ($saleCurrency === 'USD') {
                                $paymentInSaleCurrency = $paymentUsd;
                                $discountInSaleCurrency = $discountUsd;
                            } elseif ($saleCurrency === 'KHR') {
                                // Use per-payment rate_khr if available; otherwise fallback to default (change if you have a currencies table)
                                if (!is_null($p->rate_khr) && (float) $p->rate_khr > 0) {
                                    $usdToKhr = (float) $p->rate_khr; // KHR per 1 USD
                                } else {
                                    $usdToKhr = 4000.0; // sensible default; replace with your central rate if available
                                }
                                $paymentInSaleCurrency = $paymentUsd * $usdToKhr;
                                $discountInSaleCurrency = $discountUsd * $usdToKhr;
                            } else {
                                // Unknown sale currency => treat as USD
                                $paymentInSaleCurrency = $paymentUsd;
                                $discountInSaleCurrency = $discountUsd;
                            }

                            $totalPaid += (float) $paymentInSaleCurrency;
                            $totalDiscount += (float) $discountInSaleCurrency;
                        }

                        // If any payment allows overpayment, permit negative balance
                        $anyAllowOverpayment = Payment::where('sale_id', $sale->id)
                            ->where('allow_overpayment', true)
                            ->exists();

                        // compute balance (sale->grand_total expected to be in saleCurrency)
                        $rawBalance = (float) $sale->grand_total - $totalPaid;
                        $balance = $anyAllowOverpayment ? $rawBalance : max(0, $rawBalance);

                        // decide payment_status
                        if ($rawBalance <= 0) {
                            $status = 'paid';
                        } elseif ($totalPaid > 0) {
                            $status = 'partial';
                        } else {
                            $status = 'due';
                        }

                        // update sale fields: balance, payment_status, and discount (sum of payment discounts in sale currency)
                        $sale->balance = $balance;
                        $sale->payment_status = $status;
                        $sale->discount = $totalDiscount;
                        $sale->save();
                    }
                }
            });

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => __('messages.user_saved'),
                    'redirect' => route('setting.unit_convert.index'),
                    'modal' => 'action-modal',
                    'payment_id' => $payment->id ?? null,
                ]);
            }

            return redirect()
                ->route('sales.index')
                ->with('success', __('messages.user_saved'));

        } catch (\Throwable $e) {

            Log::error('Payment save error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }






    public function nextReference(Carbon $whenLocal): string
    {
        $prefix = 'PAY/' . $whenLocal->format('Y/m') . '/';
        $lastRef = Payment::where('reference_no', 'like', $prefix . '%')
            ->orderByDesc('reference_no')
            ->value('reference_no');

        $next = 1;
        if ($lastRef && preg_match('/(\d+)$/', $lastRef, $m)) {
            $next = (int) $m[1] + 1;
        }
        return $prefix . str_pad((string) $next, 4, '0', STR_PAD_LEFT);
    }


    public function index($id)
    {
        $payments = Payment::with('cashAccount')
            ->where('sale_id', $id)
            ->orderBy('date', 'desc')
            ->paginate(20);

         
        $sale = Sales::findOrFail($id);

        return view('sales.payment.index', compact('payments', 'sale'));
    }

    public function modal($id = null)
    {
        if (!$id) {
            abort(400, 'Sale id required');
        }

        $sale = Sales::findOrFail($id);


        $balance = $sale->balance;

        $paid_by = CashAccount::all();
        $currency_usd = Currencies::where('code', 'USD')->first();
        $currency_khr = Currencies::where('code', 'KHR')->first();

        $rate_usd = $currency_usd?->rate ?? 1;
        $rate_khr = $currency_khr?->rate ?? 4100;

        $amount_kh = $balance * $rate_khr;
        $amount_usd = $balance;

        return view('sales.payment.modal', compact(
            'paid_by',
            'balance',
            'sale',
            'rate_usd',
            'rate_khr',
            'amount_kh',
            'amount_usd'
        ));
    }







}
