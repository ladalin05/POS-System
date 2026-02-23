<?php

use Illuminate\Support\Facades\DB;

if (!function_exists('abaHash')) {
    function abaHash($str)
    {
        return base64_encode(hash_hmac('sha512', $str, env('ABA_PAYWAY_API_KEY'), true));
    }
}
if (!function_exists('partnerHash')) {
    function partnerHash($str, $key)
    {
        return base64_encode(hash_hmac('sha512', $str, $key, true));
    }
}
if (!function_exists('partnerHashVerify')) {
    function partnerHashVerify($str, $key, $hash)
    {
        return partnerHash($str, $key) === $hash;
    }
}
if (!function_exists('abaAction')) {
    function abaAction()
    {
        return env('ABA_PAYWAY_API_URL');
    }
}
if (!function_exists('json')) {
    function json($data)
    {
        header('Content-Type: application/json charset=utf-8');
        echo json_encode($data);
        exit;
    }
}
if (!function_exists('revoke_session')) {
    function revoke_session($user_id)
    {
        if(config('session.driver') == 'database') {
            DB::table('sessions')->where('user_id', $user_id)->delete();
        }
    }
}
if (!function_exists('can')) {
    function can($expression)
    {
        $expression = md5(trim($expression));
        $administrator = session('administrator');
        if ($administrator === true) {
            return true;
        }
        $permissions = session('permissions');
        $access = session('access');
        $expression = trim($expression);
        if (in_array($expression, array_keys($permissions))) {
            if (in_array($expression, array_keys($access))) {
                return true;
            }
            return false;
        }
        return true;
    }
}
if (!function_exists('sentTelegram')) {
    function sentTelegram($message, $parse_mode = '')
    {
        try {
            $chatId = 878264267;
            $botToken = '8114758319:AAFiFctC6avH8UYRSEFPBMLH0FdCHibxgTs';
            $url = "https://api.telegram.org/bot$botToken/sendMessage";
            $data = [
                'chat_id' => $chatId,
                'text' => $message
            ];
            if (!empty($parse_mode)) {
                $data['parse_mode'] = $parse_mode;
            }
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_exec($ch);
            curl_close($ch);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}