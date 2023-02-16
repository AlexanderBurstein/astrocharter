<?php

namespace App\Controller;
use App\Controller\Aspect;

class Composite
{
  public static function left($leftstring, $leftlength)
  {
    return (substr($leftstring, 0, $leftlength));
  }


  public static function Reduce_below_30($longitude)
  {
    $lng = $longitude;

    while ($lng >= 30) {
      $lng = $lng - 30;
    }

    return $lng;
  }

  public static function Convert_Longitude_no_secs($longitude)
  {
    $signs = array(0 => 'Ari', 'Tau', 'Gem', 'Can', 'Leo', 'Vir', 'Lib', 'Sco', 'Sag', 'Cap', 'Aqu', 'Pis');

    $sign_num = floor($longitude / 30);
    $pos_in_sign = $longitude - ($sign_num * 30);
    $deg = floor($pos_in_sign);
    $full_min = ($pos_in_sign - $deg) * 60;

    if ($deg < 10) {
      $deg = "0" . $deg;
    }

    $fmin = sprintf("%.0f", $full_min);
    if ($fmin < 10) {
      $fmin = "0" . $fmin;
    }

    return $deg . " " . $signs[$sign_num] . " " . $fmin;
  }

  public static function mid($midstring, $midstart, $midlength)
  {
    return (substr($midstring, $midstart - 1, $midlength));
  }


  public static function Find_Specific_Report_Paragraph($phrase_to_look_for, $file)
  {
    $string = "";
    $len = strlen($phrase_to_look_for);

    //put entire file contents into an array, line by line
    $file_array = file($file);

    // look through each line searching for $phrase_to_look_for
    for ($i = 0; $i < count($file_array); $i++) {
      if (self::left(trim($file_array[$i]), $len) == $phrase_to_look_for) {
        $flag = 0;
        while (trim($file_array[$i]) != "*") {
          if ($flag == 0) {
            $string .= "<b>" . $file_array[$i] . "</b>";
          } else {
            $string .= $file_array[$i];
          }
          $flag = 1;
          $i++;
        }
        break;
      }
    }

    return $string;
  }


  public static function Crunch($x)
  {
    if ($x >= 0) {
      $y = $x - floor($x / 360) * 360;
    } else {
      $y = 360 + ($x - ((1 + floor($x / 360)) * 360));
    }

    return $y;
  }

  public static function Convert_Longitude($longitude)
  {
    $signs = array(0 => 'Ari', 'Tau', 'Gem', 'Can', 'Leo', 'Vir', 'Lib', 'Sco', 'Sag', 'Cap', 'Aqu', 'Pis');

    $sign_num = floor($longitude / 30);
    $pos_in_sign = $longitude - ($sign_num * 30);
    $deg = floor($pos_in_sign);
    $full_min = ($pos_in_sign - $deg) * 60;
    $min = floor($full_min);
    $full_sec = round(($full_min - $min) * 60);

    if ($deg < 10) {
      $deg = "0" . $deg;
    }

    if ($min < 10) {
      $min = "0" . $min;
    }

    if ($full_sec < 10) {
      $full_sec = "0" . $full_sec;
    }

    return $deg . " " . $signs[$sign_num] . " " . $min . "' " . $full_sec . chr(34);
  }

  public static function GetAspectsArray()
  {
    $result = array();
    
    //Conjuction
    $asp0 = new Aspect();
    $asp0->degrees = 0.0;
    $asp0->name = "Conjunction";
    $asp0->orb = 3.0;
    $asp0->orb_for_sun = 3.0;
    $asp0->orb_for_moon = 6.0;
    $asp0->color = "blue";
    $asp0->glyph = 113;
    $result[1] = $asp0;

    //Opposition
    $asp1 = new Aspect();
    $asp1->degrees = 180.0;
    $asp1->name = "Opposition";
    $asp1->orb = 3.0;
    $asp1->orb_for_sun = 3.0;
    $asp1->orb_for_moon = 6.0;
    $asp1->color = "red";
    $asp1->glyph = 119;
    $result[2] = $asp1;

    //Trine
    $asp2 = new Aspect();
    $asp2->degrees = 120.0;
    $asp2->name = "Trine";
    $asp2->orb = 3.0;
    $asp2->orb_for_sun = 3.0;
    $asp2->orb_for_moon = 6.0;
    $asp2->color = "green";
    $asp2->glyph = 101;
    $result[3] = $asp2;

    //Square
    $asp3 = new Aspect();
    $asp3->degrees = 90.0;
    $asp3->name = "Square";
    $asp3->orb = 3.0;
    $asp3->orb_for_sun = 3.0;
    $asp3->orb_for_moon = 6.0;
    $asp3->color = "red";
    $asp3->glyph = 114;
    $result[4] = $asp3;

    //Quincunx
    $asp4 = new Aspect();
    $asp4->degrees = 150.0;
    $asp4->name = "Quincunx";
    $asp4->orb = 2.0;
    $asp4->orb_for_sun = 2.0;
    $asp4->orb_for_moon = 2.0;
    $asp4->color = "another_blue";
    $asp4->glyph = 111;
    $result[5] = $asp4;

    //Sextile
    $asp5 = new Aspect();
    $asp5->degrees = 60.0;
    $asp5->name = "Sextile";
    $asp5->orb = 3.0;
    $asp5->orb_for_sun = 3.0;
    $asp5->orb_for_moon = 6.0;
    $asp5->color = "green";
    $asp5->glyph = 116;
    $result[6] = $asp5;

    //Semi-sextile
    $asp6 = new Aspect();
    $asp6->degrees = 30.0;
    $asp6->name = "Semi-sextile";
    $asp6->orb = 1.2;
    $asp6->orb_for_sun = 1.2;
    $asp6->orb_for_moon = 1.2;
    $asp6->color = "another_blue";
    $asp6->glyph = 105;
    $result[7] = $asp6;

    //Semi-square
    $asp7 = new Aspect();
    $asp7->degrees = 45.0;
    $asp7->name = "Semi-square";
    $asp7->orb = 1.2;
    $asp7->orb_for_sun = 1.2;
    $asp7->orb_for_moon = 1.2;
    $asp7->color = "another_blue";
    $asp7->glyph = 121;
    $result[8] = $asp7;

    //Quintile
    $asp8 = new Aspect();
    $asp8->degrees = 72.0;
    $asp8->name = "Quintile";
    $asp8->orb = 1.0;
    $asp8->orb_for_sun = 1.0;
    $asp8->orb_for_moon = 1.0;
    $asp8->color = "violet";
    $asp8->glyph = 117;
    $result[9] = $asp8;

    //Biquintile
    $asp9 = new Aspect();
    $asp9->degrees = 144.0;
    $asp9->name = "Biquintile";
    $asp9->orb = 1.0;
    $asp9->orb_for_sun = 1.0;
    $asp9->orb_for_moon = 1.0;
    $asp9->color = "violet";
    $asp9->glyph = 110;
    $result[10] = $asp9;

    return $result;
    
  }
  
  public static function GetJulianDate($inhours, $inmins, $insecs, $inmonth, $inday, $inyear) {
    $difference = 13;
    if ($inyear < 1900 || ($inyear == 1900 && $inmonth < 3) || ($inyear == 1900 && $inmonth == 3 && $inday < 14)) {
      $difference = 12;
      if ($inyear < 1800 || ($inyear == 1800 && $inmonth < 3) || ($inyear == 1800 && $inmonth == 3 && $inday < 13)) {
          $difference = 11;
          if ($inyear < 1700 || ($inyear == 1700 && $inmonth < 3) || ($inyear == 1700 && $inmonth == 3 && $inday < 12))
          {
            $difference = 10;
            if ($inyear < 1582 || ($inyear == 1582 && $inmonth < 10) || ($inyear == 1582 && $inmonth == 10 && $inday < 16))
              $difference = 0;
          }
      }
    }
    $result = juliantojd($inmonth, $inday, $inyear) - $difference - 0.5
      + ($inhours * 3600 + $inmins * 60 + $insecs) / 86400;
    return $result;
  }
}