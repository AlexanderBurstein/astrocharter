<?php

namespace App\Controller;

use App\Controller\Composite;
use App\DataFixtures\PlaceFixture;
use App\Entity\Person;

class Transit
{
  public $transit_wheel_path;
  
  public function draw(Person $person)
  {
    $result = '';
    $h_sys = 'p'; //ToDo: modify to use other options - see composite.php
    $name = $person->getFullname();

    $birthdate = $person->getBirthdate();

    $month = intval($birthdate->format('n'));
    $day = intval($birthdate->format('j'));
    $year = intval($birthdate->format('Y'));
    $transit_month = date("m");
    $transit_day = date("d");
    $transit_year = date("Y");

    $hour = 12;
    $minute = 0;
    $transit_hour = intval($birthdate->format('G'));
    $transit_minute = intval($birthdate->format('i'));

    $timezone = intval(TimezoneHelper::getOffset($person->getTimezone()));

    $place = $person->getPlace();
    if (empty($place))
        $place = (new PlaceFixture())->getTestCity();

    $longitude = $place->getLongitude();
    $long_deg = intval(substr($longitude, 1, 3));
    $long_min = intval(substr($longitude, 5, 2));
    $long_secs = intval(substr($longitude, 8, 2));

    $latitude = $place->getLatitude();
    $lat_deg = intval(substr($latitude, 1, 2));
    $lat_min = intval(substr($latitude, 4, 2));
    $lat_secs = intval(substr($latitude, 7, 2));

    if (substr($longitude, 0, 1) == "W") {
      $ew_txt = "w";
      $ew = -1;
    } else {
      $ew_txt = "e";
      $ew = 1;
    }

    if (substr($latitude, 0, 1) == "N") {
      $ns_txt = "n";
      $ns = 1;
    } else {
      $ns_txt = "s";
      $ns = -1;
    }

    //$timezone = -$timezone;             //because in AstroWin and Astro123, west longitude is a positive number and time zone is a positive number

    // calculate astronomic data
    $swephsrc = './src/Services/sweph';        //sweph MUST be in a folder no less than at this level
    if (!realpath($swephsrc)) {
      $swephsrc = '.' . $swephsrc;
      $services_path = '../src/Services';
    }
    else
      $services_path = './src/Services';
    $sweph = $swephsrc;

    // Unset any variables not initialized elsewhere in the program
    unset($PATH, $out, $pl_name, $longitude1, $house_pos);

    //assign data from database to local variables
    $innmonth = $month;
    $innday = $day;
    $innyear = $year;
    $intmonth = $transit_month;
    $intday = $transit_day;
    $intyear = $transit_year;

    $innhours = $hour;
    $innmins = $minute;
    $innsecs = "0";
    $inthours = $transit_hour;
    $intmins = $transit_minute;
    $intsecs = "0";

    $intz = $timezone;

    $my_longitude = $ew * ($long_deg + ($long_min / 60) + ($long_secs / 3600));
    $my_latitude = $ns * ($lat_deg + ($lat_min / 60) + ($lat_secs / 3600));

    $abs_tz = abs($intz);
    $the_hours = floor($abs_tz);
    $the_minutes = explode(':', TimezoneHelper::getOffset($person->getTimezone()))[1];

    if ($intz >= 0) {
      $innhours = $innhours - $the_hours;
      $innmins = $innmins - $the_minutes;
    } else {
      $innhours = $innhours + $the_hours;
      $innmins = $innmins + $the_minutes;
    }

    // adjust date and time for minus hour due to time zone taking the hour negative
    $utdatenat = strftime("%d.%m.%Y", mktime($innhours, $innmins, $innsecs, $innmonth, $innday, $innyear));
    $utdatetrans = strftime("%d.%m.%Y", mktime($inthours, $intmins, $intsecs, $intmonth, $intday, $intyear));
     $utnat = strftime("%H:%M:%S", mktime($innhours, $innmins, $innsecs, $innmonth, $innday, $innyear));
     $uttrans = strftime("%H:%M:%S", mktime($inthours, $intmins, $intsecs, $intmonth, $intday, $intyear));
    $julian_date_nat = Composite::GetJulianDate($innhours, $innmins, $innsecs, $innmonth, $innday, $innyear);
    $julian_date_trans = Composite::GetJulianDate($inthours, $intmins, $intsecs, $intmonth, $intday, $intyear);

    $PATH = getenv('PATH');
    putenv("PATH=$PATH:$swephsrc");
    // get LAST_TPLANET planets and all house cusps
    if (strlen($h_sys) != 1) {
      $h_sys = "p";
    }

    $command_line_nat = "swetest -edir$sweph -j$julian_date_nat -ut$utnat -p0123456789DAttt -eswe -house$my_longitude,$my_latitude,$h_sys -flsj -g, -head";
    exec($command_line_nat, $out_nat);

    $command_line_trans = "swetest -edir$sweph -j$julian_date_trans -ut$uttrans -p0123456789DAttt -eswe -house$my_longitude,$my_latitude,$h_sys -flsj -g, -head";
    exec($command_line_trans, $out_trans);

    // Each line of output data from swetest is exploded into array $row, giving these elements:
    // 0 = longitude
    // 1 = speed
    // 2 = house position
    // planets are index 0 - index (LAST_TPLANET), house cusps are index (LAST_TPLANET + 1) - (LAST_TPLANET + 12)
    foreach ($out_nat as $key => $line) {
      $row = explode(',', $line);
	if ($key >= 10) break;
      if (count($row) > 0)
        $longitude1[$key] = $row[0];
      if (count($row) > 1)
        $speed1[$key] = $row[1];
    };

    foreach ($out_trans as $key => $line) {
      $row = explode(',', $line);
       if ($key <= 10 ||  $key > 14) {
      	if (count($row) > 0) 
           $longitude1[] = $row[0];
      if (count($row) > 1)
        $speed1[] = $row[1];
       }
    };

    include("constants_trans.php");            // this is here because we must rename the planet names
//calculate the Part of Fortune
    //is this a day chart or a night chart?
    if ($longitude1[LAST_TPLANET + 1] > $longitude1[LAST_TPLANET + 7]) {
      if ($longitude1[SE_TSUN] <= $longitude1[LAST_TPLANET + 1] And $longitude1[SE_TSUN] > $longitude1[LAST_TPLANET + 7]) {
        $day_chart = True;
      } else {
        $day_chart = False;
      }
    } else {
      if ($longitude1[SE_TSUN] > $longitude1[LAST_TPLANET + 1] And $longitude1[SE_TSUN] <= $longitude1[LAST_TPLANET + 7]) {
        $day_chart = False;
      } else {
        $day_chart = True;
      }
    }

    if ($day_chart == True) {
      $longitude1[SE_TPOF] = $longitude1[LAST_TPLANET + 1] + $longitude1[SE_TMOON] - $longitude1[SE_TSUN];
    } else {
      $longitude1[SE_TPOF] = $longitude1[LAST_TPLANET + 1] - $longitude1[SE_TMOON] + $longitude1[SE_TSUN];
    }

    if ($longitude1[SE_TPOF] >= 360) {
      $longitude1[SE_TPOF] = $longitude1[SE_TPOF] - 360;
    }

    if ($longitude1[SE_TPOF] < 0) {
      $longitude1[SE_TPOF] = $longitude1[SE_TPOF] + 360;
    }
    $ubt1 = 0;

//get house positions of planets here
    for ($x = 1; $x <= 12; $x++) {
      for ($y = 0; $y <= LAST_TPLANET; $y++) {
        $pl = $longitude1[$y] + (1 / 36000);
        if ($x < 12 And $longitude1[$x + LAST_TPLANET] > $longitude1[$x + LAST_TPLANET + 1]) {
          If (($pl >= $longitude1[$x + LAST_TPLANET] And $pl < 360) Or ($pl < $longitude1[$x + LAST_TPLANET + 1] And $pl >= 0)) {
            $house_pos1[$y] = $x;
            continue;
          }
        }

        if ($x == 12 And ($longitude1[$x + LAST_TPLANET] > $longitude1[LAST_TPLANET + 1])) {
          if (($pl >= $longitude1[$x + LAST_TPLANET] And $pl < 360) Or ($pl < $longitude1[LAST_TPLANET + 1] And $pl >= 0)) {
            $house_pos1[$y] = $x;
          }
          continue;
        }

        if (($pl >= $longitude1[$x + LAST_TPLANET]) And ($pl < $longitude1[$x + LAST_TPLANET + 1]) And ($x < 12)) {
          $house_pos1[$y] = $x;
          continue;
        }

        if (($pl >= $longitude1[$x + LAST_TPLANET]) And ($pl < $longitude1[LAST_TPLANET + 1]) And ($x == 12)) {
          $house_pos1[$y] = $x;
        }
      }
    }

//display transit data
    $secs = "0";
    if ($timezone < 0) {
      $tz = $timezone;
    } else {
      $tz = "+" . $timezone;
    }

    $restored_name = stripslashes($name);
      if (!empty($restored_name))
          $restored_name .= ", born ";

    $line1 = $restored_name . strftime("%A, %B %d, %Y at %H:%M", mktime(intval($hour), intval($minute), intval($secs), intval($month), intval($day), intval($year)));
    $line1 .= ' (time zone = GMT ' . TimezoneHelper::getOffset($person->getTimezone()) . ')';
    $line1 .= " at " . $long_deg . $ew_txt . sprintf("%02d", $long_min) . " and " . $lat_deg . $ns_txt . sprintf("%02d", $lat_min);
    
    $ubt2 = $ubt1;

    $rx1 = "";
    for ($i = 0; $i <= LAST_TPLANET; $i++) {
      if ($speed1[$i] < 0) {
        $rx1 .= "R";
      } else {
        $rx1 .= " ";
      }
    }

    $rx2 = $rx1;

    for ($i = 1; $i <= LAST_TPLANET; $i++) {
      $hc1[$i] = $longitude1[LAST_TPLANET + $i];
    }

    $ser_L1 = serialize($longitude1);
    $ser_L2 = serialize($longitude1);
    $ser_hc1 = serialize($hc1);
    $ser_hpos = serialize($house_pos1);

    $wheel_width = 640;
    $wheel_height = $wheel_width + 50;        //includes space at top of wheel for header

    $personId = $person->getId();
    $this->transit_wheel_path = (empty($personId) ? '' : '../') . Transit_wheel::getImage($rx1, $ser_L1, $ser_hc1, $ser_hpos, $line1, $ubt1, $personId);
  }

    public static function Find_Specific_Report_Paragraph_ASPECTS($phrase_to_look_for, $file, $x, $y, $p_h)
  {
    $string = "";
    $len = strlen($phrase_to_look_for);

    //put entire file contents into an array, line by line
    $file_array = file($file);

    // look through each line searching for $phrase_to_look_for
    for ($i = 0; $i < count($file_array); $i++) {
      if (Composite::left(trim($file_array[$i]), $len) == $phrase_to_look_for) {
        $flag = 0;
        while (trim($file_array[$i]) != "*") {
          if ($flag == 0) {
            if ($p_h[$y][$x] == 0) {
              $t = " (power = " . sprintf("%.2f", $p_h[$x][$y]) . " and this aspect is neutral)";
            }
            if ($p_h[$y][$x] > 0) {
              $t = " (power = " . sprintf("%.2f", $p_h[$x][$y]) . " and this aspect is harmonious = " . sprintf("%.2f", $p_h[$y][$x]) . ")";
            }
            if ($p_h[$y][$x] < 0) {
              $t = " (power = " . sprintf("%.2f", $p_h[$x][$y]) . " and this aspect is discordant = " . sprintf("%.2f", $p_h[$y][$x]) . ")";
            }

            $string .= "<b>" . Composite::left($file_array[$i], strlen($file_array[$i]) - 1) . "</b>" . $t . "\n";
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
}