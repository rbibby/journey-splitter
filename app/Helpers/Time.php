<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class Time
{
    public static function inHoursAndMinutes($minutes)
    {
        if ($minutes < 60) {
            return round($minutes) . ' minutes';
        }

        $hours = floor($minutes / 60);
        $hoursInMinutes = $hours * 60;

        $minutesLeft = floor($minutes - $hoursInMinutes);

        return $hours . ' ' . Str::plural('hour', $hours) . ', ' . $minutesLeft . ' ' . Str::plural('minute', $minutesLeft);
    }
}