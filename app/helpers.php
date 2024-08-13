<?php

use Carbon\Carbon;



if (!function_exists('formatTime')) {
    function formatTime($time){
        $formattedTime = date('g:i A', strtotime($time));
        $timeParts = explode(' ', $formattedTime);
        $time = $timeParts[0];
        $amPm = $timeParts[1];
        return ($amPm == 'AM') ? $time . ' صباحاً' : $time . ' مساءً';
    }
}


if (!function_exists('logActivity')) {
    function logActivity($action, $type){
        if (isset(auth()->user()->id)) {
            \App\Models\ActivityLog::create([
                'employee_id' => auth()->user()->id,
                'action' => $action,
                'type' => $type,
            ]);
        }
        }
}


if (!function_exists('formatArabicDate')) {
    function formatArabicDate($date){
        $arabicMonths = [ 'يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو', 'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'];
        if (is_string($date)) {
            $date = new Carbon($date);
        }
        if (!$date instanceof Carbon) {
            throw new Exception('The date must be an instance of Carbon or a valid date string.');
        }
        $monthName = $arabicMonths[$date->month - 1];
        $day = $date->format('d');
        $year = $date->format('Y');
        return "{$day} {$monthName} {$year}";
    }
}