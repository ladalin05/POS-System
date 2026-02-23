<?php

use Illuminate\Support\Facades\DB;

if (!function_exists('yesNo')) {
    function yesNo($instance)
    {
        return $instance ? '<span class="badge bg-success bg-opacity-10 text-success">Yes</span>' : '<span class="badge bg-danger bg-opacity-10 text-danger">No</span>';
    }
}
if (!function_exists('badge')) {
    function badge($text, $status = 'success')
    {
        return '<span class="badge bg-' . $status . ' bg-opacity-10 text-' . $status . '">' . $text . '</span>';
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
if (!function_exists('date_period')) {
    function date_period($start_date, $end_date, $timezone = 'UTC')
    {
        $start_date = date('Y-m-d', strtotime($start_date));
        $end_date = date('Y-m-d', strtotime($end_date));
        $timezone = new DateTimeZone($timezone);
        $begin = new DateTime($start_date, $timezone);
        $end = new DateTime($end_date, $timezone);
        $interval = new DateInterval('P1D');
        $periods = new DatePeriod($begin, $interval, $end);
        $monthsArray = [];
        $m = 0;
        $d = 0;
        $w = 0;
        $days = 0;
        $text = '';
        $text_kh = '';
        $daysBefore = 0;
        $daysAfter = 0;
        $data = [];
        foreach ($periods as $period) {
            if ($period->format('Y-m-t') < $end_date && $period->format('Y-m-01') > $start_date) {
                $monthsArray[$period->format('Y-m-t')] = $period->format('t');
            }
        }
        $first = min(array_keys($monthsArray));
        $last = max(array_keys($monthsArray));
        $first = date('Y-m-01', strtotime($first));
        $before = new DateTime($first, $timezone);
        $after = new DateTime($last, $timezone);
        $after->modify('+1 day');
        $m = count($monthsArray);
        $daysBefore = $begin->diff($before)->days;
        $daysAfter = $after->diff($end)->days;
        $d = $daysBefore + $daysAfter;
        $days = $d + array_sum($monthsArray);
        $w = (int)round($days / 7);
        foreach ($monthsArray as $key => $day) {
            $data[strtotime($key)] = date('F', strtotime($key)) . ': ' . ($day > 1 ? $day . ' days' : $day . ' day');
        }
        array_unshift($data, date('F', strtotime($start_date)) . ': ' . $daysBefore . ' days');
        array_push($data, date('F', strtotime($end_date)) . ': ' . $daysAfter . ' days');
        if ($m > 0) {
            $text .= $m . ($m > 1 ? ' Months ' : ' Month ');
            $text_kh .= $m . ' ខែ ';
        }
        if ($d > 0) {
            $text .= $d . ($d > 1 ? ' Days ' : ' Day ');
            $text_kh .= $d . ' ថ្ងៃ ';
        }
        return [
            'm' => $m,
            'd' => $d,
            'w' => $w,
            'days' => $days,
            'text' => trim($text),
            'text_kh' => trim($text_kh),
            'begin' => $start_date,
            'end' => $end_date,
            'timezone' => $timezone,
            'data' => $data,
        ];
    }
}
if (!function_exists('clipboard')) {
    function clipboard($instance)
    {
        return '<i class="ph ph-copy me-3 text-primary cursor-pointer" clipboard-text="' . $instance . '" onclick="copyToClipboard(event)"></i>';
    }
}