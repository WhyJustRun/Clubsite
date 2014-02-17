<?php
class TimeLib
{
    // Combines a date of format YYYY-MM-DD and time of format HH:MM into a mysql datetime
    public static function mysqlDateTimeFormat($date, $time = null) {
        if (empty($time)) {
            return $date . " 00:00:00";
        } else {
            return $date . " " . $time . ":00";
        }
    }
}
