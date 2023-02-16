<?php

namespace App\Controller;

use DateTime;
use DateTimeZone;

class TimezoneHelper
{
    static function toGmtOffset($timezone){
        return sprintf("(GMT%s)", self::getOffset($timezone));
    }
    
    static function getOffset($timezone)
    {
        $userTimeZone = new DateTimeZone($timezone);
        $utcDateTime = new DateTime("now", $userTimeZone);
        return $utcDateTime->format("P");
    }
    
    static function getCustomFlippedTimeZones($tzToSelect)
    {
        $result = array();
        
        foreach (DateTimeZone::listIdentifiers() as $timezone) {
            $result[] = ($timezone == $tzToSelect ? "(!)" : "")  . self::toGmtOffset($timezone) . " " . $timezone;
        }
        
        asort($result);
        foreach ($result as &$timezone)
        {
            $gmtPartEnd = strpos($timezone, ") ") + 2;
            $timezone = substr($timezone, $gmtPartEnd);
        }
        
        return $result;
    }
}