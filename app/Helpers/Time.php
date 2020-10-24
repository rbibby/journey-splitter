<?php

namespace App\Helpers;

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

        return $hours . ' hours, ' . $minutesLeft . ' minutes';
    }
}