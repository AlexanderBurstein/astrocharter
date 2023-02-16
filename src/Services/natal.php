<?php

namespace App\Controller;

use App\Controller\Composite;
use App\DataFixtures\PlaceFixture;
use App\Entity\Person;

class Natal
{

  private static $no_interps = False;                   //set this to False when you want interpretations
  public $natal_wheel_path;
  public $natal_aspect_grid_path;
  public $natal_explanation;
  public $planet_explanations;
  public $sign_explanations;
  public $offset_x_sign_explanation_border;
  public $planet_texts;
  public $sign_texts;
  
  public function draw(Person $person)
  {
    $result = '';
    $h_sys = 'p'; //ToDo: modify to use other options - see composite.php
    $name = $person->getFullname();

    $birthdate = $person->getBirthdate();

    $month = intval($birthdate->format('n'));
    $day = intval($birthdate->format('j'));
    $year = intval($birthdate->format('Y'));

    $hour = intval($birthdate->format('G'));
    $minute = intval($birthdate->format('i'));

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
    $inmonth = $month;
    $inday = $day;
    $inyear = $year;

    $inhours = $hour;
    $inmins = $minute;
    $insecs = "0";

    $intz = $timezone;

    $my_longitude = $ew * ($long_deg + ($long_min / 60) + ($long_secs / 3600));
    $my_latitude = $ns * ($lat_deg + ($lat_min / 60) + ($lat_secs / 3600));

    $abs_tz = abs($intz);
    $the_hours = floor($abs_tz);
    $the_minutes = explode(':', TimezoneHelper::getOffset($person->getTimezone()))[1];

    if ($intz >= 0) {
      $inhours = $inhours - $the_hours;
      $inmins = $inmins - $the_minutes;
    } else {
      $inhours = $inhours + $the_hours;
      $inmins = $inmins + $the_minutes;
    }

    // adjust date and time for minus hour due to time zone taking the hour negative
    $utdatenow = strftime("%d.%m.%Y", mktime($inhours, $inmins, $insecs, $inmonth, $inday, $inyear));
     $utnow = strftime("%H:%M:%S", mktime($inhours, $inmins, $insecs, $inmonth, $inday, $inyear));
    $julian_date = Composite::GetJulianDate($inhours, $inmins, $insecs, $inmonth, $inday, $inyear);

    $PATH = getenv('PATH');
    putenv("PATH=$PATH:$swephsrc");
    // get LAST_PLANET planets and all house cusps
    if (strlen($h_sys) != 1) {
      $h_sys = "p";
    }

    $command_line = "swetest -edir$sweph -j$julian_date -ut$utnow -p0123456789DAttt -eswe -house$my_longitude,$my_latitude,$h_sys -flsj -g, -head";
    exec($command_line, $out);

    // Each line of output data from swetest is exploded into array $row, giving these elements:
    // 0 = longitude
    // 1 = speed
    // 2 = house position
    // planets are index 0 - index (LAST_PLANET), house cusps are index (LAST_PLANET + 1) - (LAST_PLANET + 12)
    foreach ($out as $key => $line) {
      $row = explode(',', $line);
      if (count($row) > 0)
        $longitude1[$key] = $row[0];
      if (count($row) > 1)
        $speed1[$key] = $row[1];
      if (count($row) > 2)
        $house_pos1[$key] = $row[2];
    };

    include("constants_eng.php");            // this is here because we must rename the planet names

    //calculate the Part of Fortune
    //is this a day chart or a night chart?
    if ($longitude1[LAST_PLANET + 1] > $longitude1[LAST_PLANET + 7]) {
      if ($longitude1[0] <= $longitude1[LAST_PLANET + 1] And $longitude1[0] > $longitude1[LAST_PLANET + 7]) {
        $day_chart = True;
      } else {
        $day_chart = False;
      }
    } else {
      if ($longitude1[0] > $longitude1[LAST_PLANET + 1] And $longitude1[0] <= $longitude1[LAST_PLANET + 7]) {
        $day_chart = False;
      } else {
        $day_chart = True;
      }
    }

    if ($day_chart == True) {
      $longitude1[SE_POF] = $longitude1[LAST_PLANET + 1] + $longitude1[1] - $longitude1[0];
    } else {
      $longitude1[SE_POF] = $longitude1[LAST_PLANET + 1] - $longitude1[1] + $longitude1[0];
    }

    if ($longitude1[SE_POF] >= 360) {
      $longitude1[SE_POF] = $longitude1[SE_POF] - 360;
    }

    if ($longitude1[SE_POF] < 0) {
      $longitude1[SE_POF] = $longitude1[SE_POF] + 360;
    }

//add a planet - maybe some code needs to be put here

    //capture the Vertex longitude
    $longitude1[LAST_PLANET] = $longitude1[LAST_PLANET + 16];        //Asc = +13, MC = +14, RAMC = +15, Vertex = +16


    $hr_ob = $hour;
    $min_ob = $minute;

    $ubt1 = 0;
    if (($hr_ob == 12) And ($min_ob == 0)) {
      $ubt1 = 1;
    }                // this person has an unknown birth time


    if ($ubt1 == 1) {
      $longitude1[1 + LAST_PLANET] = 0;        //make flat chart with natural houses
      $longitude1[2 + LAST_PLANET] = 30;
      $longitude1[3 + LAST_PLANET] = 60;
      $longitude1[4 + LAST_PLANET] = 90;
      $longitude1[5 + LAST_PLANET] = 120;
      $longitude1[6 + LAST_PLANET] = 150;
      $longitude1[7 + LAST_PLANET] = 180;
      $longitude1[8 + LAST_PLANET] = 210;
      $longitude1[9 + LAST_PLANET] = 240;
      $longitude1[10 + LAST_PLANET] = 270;
      $longitude1[11 + LAST_PLANET] = 300;
      $longitude1[12 + LAST_PLANET] = 330;
    }


//get house positions of planets here
    for ($x = 1; $x <= 12; $x++) {
      for ($y = 0; $y <= LAST_PLANET; $y++) {
        $pl = $longitude1[$y] + (1 / 36000);
        if ($x < 12 And $longitude1[$x + LAST_PLANET] > $longitude1[$x + LAST_PLANET + 1]) {
          If (($pl >= $longitude1[$x + LAST_PLANET] And $pl < 360) Or ($pl < $longitude1[$x + LAST_PLANET + 1] And $pl >= 0)) {
            $house_pos1[$y] = $x;
            continue;
          }
        }

        if ($x == 12 And ($longitude1[$x + LAST_PLANET] > $longitude1[LAST_PLANET + 1])) {
          if (($pl >= $longitude1[$x + LAST_PLANET] And $pl < 360) Or ($pl < $longitude1[LAST_PLANET + 1] And $pl >= 0)) {
            $house_pos1[$y] = $x;
          }
          continue;
        }

        if (($pl >= $longitude1[$x + LAST_PLANET]) And ($pl < $longitude1[$x + LAST_PLANET + 1]) And ($x < 12)) {
          $house_pos1[$y] = $x;
          continue;
        }

        if (($pl >= $longitude1[$x + LAST_PLANET]) And ($pl < $longitude1[LAST_PLANET + 1]) And ($x == 12)) {
          $house_pos1[$y] = $x;
        }
      }
    }

//display natal data
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
    
    $hr_ob = $hour;
    $min_ob = $minute;

    $ubt1 = 0;
    if (($hr_ob == 12) And ($min_ob == 0)) {
      $ubt1 = 1;                // this person has an unknown birth time
    }

    $ubt2 = $ubt1;

    $rx1 = "";
    for ($i = 0; $i <= LAST_PLANET; $i++) {
      if ($speed1[$i] < 0) {
        $rx1 .= "R";
      } else {
        $rx1 .= " ";
      }
    }

    $rx2 = $rx1;

    for ($i = 1; $i <= LAST_PLANET; $i++) {
      $hc1[$i] = $longitude1[LAST_PLANET + $i];
    }

    $ser_L1 = serialize($longitude1);
    $ser_L2 = serialize($longitude1);
    $ser_hc1 = serialize($hc1);
    $ser_hpos = serialize($house_pos1);

    $wheel_width = 640;
    $wheel_height = $wheel_width + 50;        //includes space at top of wheel for header

    $personId = $person->getId();
    $this->natal_wheel_path = (empty($personId) ? '' : '../') . Natal_wheel::getImage($rx1, $ser_L1, $ser_hc1, $ser_hpos, $line1, $ubt1, empty($restored_name) ? null : $personId);
    if (empty($restored_name))
	rename("natal_wheel.png", "../AstroExtension/images/natal_wheel.png");
    $this->natal_aspect_grid_path = (empty($personId) ? '' : '../') . Natal_aspect_grid::getImage($rx1, $ser_L1, $ser_hc1, $ubt1, $this, $personId);

//display natal data
    $result .= '<center><table width="40%" cellpadding="0" cellspacing="0" border="0">';

    $result .= '<tr>';
    $result .= "<td><font color='#0000ff'><b> Planet </b></font></td>";
    $result .= "<td><font color='#0000ff'><b> Longitude </b></font></td>";
    if ($ubt1 == 1) {
      $result .= "<td>&nbsp;</td>";
    } else {
      $result .= "<td><font color='#0000ff'><b> House<br>position </b></font></td>";
    }
    $result .= '</tr>';

    if ($ubt1 == 1) {
      $a1 = SE_TNODE;
    } else {
      $a1 = LAST_PLANET;
    }

    for ($i = 0; $i <= $a1; $i++) {
      $result .= '<tr>';
      $result .= "<td>" . $pl_name[$i] . "</td>";
      $result .= "<td><font face='Courier New'>" . Composite::Convert_Longitude($longitude1[$i]) . " " .
          Composite::Mid($rx1, $i + 1, 1) . "</font></td>";
      if ($ubt1 == 1) {
        $result .= "<td>&nbsp;</td>";
      } else {
        $hse = floor($house_pos1[$i]);
        if ($hse < 10) {
          $result .= "<td>&nbsp;&nbsp;&nbsp;&nbsp; " . $hse . "</td>";
        } else {
          $result .= "<td>&nbsp;&nbsp;&nbsp;" . $hse . "</td>";
        }
      }
      $result .= '</tr>';
    }

    $result .= '<tr>';
    $result .= "<td> &nbsp </td>";
    $result .= "<td> &nbsp </td>";
    $result .= "<td> &nbsp </td>";
    $result .= "<td> &nbsp </td>";
    $result .= '</tr>';

    if ($ubt1 == 0) {
      $result .= '<tr>';
      $result .= "<td><font color='#0000ff'><b> House </b></font></td>";
      $result .= "<td><font color='#0000ff'><b> Longitude </b></font></td>";
      $result .= "<td> &nbsp </td>";
      $result .= '</tr>';

      for ($i = LAST_PLANET + 1; $i <= LAST_PLANET + 12; $i++) {
        $result .= '<tr>';
        if ($i == LAST_PLANET + 1) {
          $result .= "<td>Ascendant </td>";
        } elseif ($i == LAST_PLANET + 10) {
          $result .= "<td>MC (Midheaven) </td>";
        } else {
          $result .= "<td>House " . ($i - LAST_PLANET) . "</td>";
        }
        $result .= "<td><font face='Courier New'>" . Composite::Convert_Longitude($longitude1[$i]) . "</font></td>";
        $result .= "<td> &nbsp </td>";
        $result .= '</tr>';
      }
    }

    $result .= '</table></center>';
    $result .= "<br><br>";


    // display natal data - aspect table
    $result .= '<center><table width="40%" cellpadding="0" cellspacing="0" border="0">';

    $result .= '<tr>';
    $result .= "<td><font color='#0000ff'><b> Planet </b></font></td>";
    $result .= "<td><font color='#0000ff'><b> Aspect </b></font></td>";
    $result .= "<td><font color='#0000ff'><b> Planet </b></font></td>";
    $result .= "<td><font color='#0000ff'><b> Orb </b></font></td>";
    $result .= '</tr>';

    // include Ascendant and MC
    $longitude1[LAST_PLANET + 1] = $hc1[1];
    $longitude1[LAST_PLANET + 2] = $hc1[10];

    $pl_name[LAST_PLANET + 1] = "Ascendant";
    $pl_name[LAST_PLANET + 2] = "Midheaven";

    if ($ubt1 == 1) {
      $a1 = SE_TNODE;
    } else {
      $a1 = LAST_PLANET + 2;
    }

    $aspects = Composite::GetAspectsArray();

// draw in the aspect lines
    for ($i = 0; $i <= $a1; $i++) {
      $result .= "<tr><td colspan='4'>&nbsp;</td></tr>";
      for ($j = $i + 1; $j <= $a1; $j++) {
        $da = Abs($longitude1[$i] - $longitude1[$j]);
        if ($da > 180) {
          $da = 360 - $da;
        }

        for($q = 1; $q <= count($aspects); $q++) {
          $orb = $aspects[$q]->orb;
          if ($i == SE_SUN)
            $orb = $aspects[$q]->orb_for_sun;
          elseif ($i == SE_MOON)
            $orb = $aspects[$q]->orb_for_moon;
          if (($i == SE_POF Or $j == SE_POF) And $orb > 2)
            $orb = 2;
          if ($da <= $aspects[$q]->degrees + $orb And $da >= $aspects[$q]->degrees - $orb) {
            if ($ubt1 == 0 Or ($ubt1 != 0 And $i <= SE_TNODE And $j <= SE_TNODE)) {
              $result .= '<tr>';
              $result .= "<td>" . $pl_name[$i] . "</td>";
              $result .= "<td>" . $aspects[$q]->name . "</td>";
              $result .= "<td>" . $pl_name[$j] . "</td>";
              $result .= "<td>" . sprintf("%.2f", abs($da)) . "</td>";
              $result .= '</tr>';
            }
          }
        }
      }
    }

    $result .= '</table></center>';
    $result .= "<br><br>";


//display the natal chart report
      $result .= '<center><table width="90%" cellpadding="0" cellspacing="0" border="0">';
      $result .= '<tr><td><font face="Verdana" size="3">';

      //display philosophy of astrology
      $result .= "<center><font size='+1' color='#0000ff'><b>PHILOSOPHY OF ASTROLOGY</b></font></center>";

      $file = $services_path . "/natal_files/philo.txt";
      $fh = fopen($file, "r");
      $string = fread($fh, filesize($file));
      fclose($fh);

      $philo = nl2br($string);
      $result .= "<font size='3'>" . $philo . "</font>";

      $this->planet_texts = array();
      for ($p = 0; $p <= LAST_PLANET; ++$p)
        $this->planet_texts[$pl_name[$p]] = '';
      $this->sign_texts = array();
      for ($s = 1; $s <= 12; ++$s)
        $this->sign_texts[$name_of_sign[$s]] = '';
      if ($ubt1 == 0) {
        //display rising sign interpretation
        //get header first
        $result .= "<center><font size='+1' color='#0000ff'><b>THE RISING SIGN OR ASCENDANT</b></font></center>";

        $file = $services_path . "/natal_files/ascsign.txt";
        $fh = fopen($file, "r");
        $string = fread($fh, filesize($file));
        fclose($fh);

        $result .= "<br>";
        $result .= "<font size='3'>" . $string . "</font>";
        $result .= "<br><br><b>" . " YOUR ASCENDANT IS: <br><br>" . "</b>";

        $s_pos = floor($hc1[1] / 30) + 1;
        $phrase_to_look_for = $sign_name[$s_pos] . " rising";
        $file = $services_path . "/natal_files/rising.txt";
        $string = Composite::Find_Specific_Report_Paragraph($phrase_to_look_for, $file);
        $string = nl2br($string);

        $this->sign_texts[$name_of_sign[$s_pos]] .= "<font size='3''>" . $string . "</font>";
        $result .= "<font size='3'>" . $string . "</font>";
      }


      //display planetary aspect interpretations
      //get header first
      $result .= "<center><font size='+1' color='#0000ff'><b>PLANETARY ASPECTS</b></font></center>";

      $file = $services_path . "/natal_files/aspect.txt";
      $fh = fopen($file, "r");
      $string = fread($fh, filesize($file));
      fclose($fh);

      $string = nl2br($string);
      $p_aspect_interp = $string;

      $result .= "<font size='3'>" . $p_aspect_interp . "</font>";

      // get the individual power and harmony for each aspect
      Dyne_aspect_p_h::GetAspectPowerHarmony($longitude1, $house_pos1, $ubt1, $p_h, LAST_PLANET);

      $num_aspects = 0;
      $aspect_text = array();

      // loop through each planet
      for ($i = 0; $i <= LAST_PLANET; $i++)            //was 8
      {
        for ($j = $i + 1; $j <= LAST_PLANET; $j++)            //was 9
        {
          if (($i == 1 Or $i == SE_POF Or $i == SE_VERTEX Or $i == LAST_PLANET + 1 Or $i == LAST_PLANET + 2 Or $j == 1 Or $j == SE_POF Or $j == SE_VERTEX Or $j == LAST_PLANET + 1 Or $j == LAST_PLANET + 2) And $ubt1 == 1) {
            continue;            // do not allow Moon aspects or PoF or Vertex aspects if birth time is unknown
          }

          $da = Abs($longitude1[$i] - $longitude1[$j]);
          if ($da > 180) {
            $da = 360 - $da;
          }

          for($q = 1; $q <= 6; $q++) {
            $orb = $aspects[$q]->orb;
            if ($i == SE_SUN)
              $orb = $aspects[$q]->orb_for_sun;
            elseif ($i == SE_MOON)
              $orb = $aspects[$q]->orb_for_moon;
            if ($da <= $aspects[$q]->degrees + $orb And $da >= $aspects[$q]->degrees - $orb) {
              if ($ubt1 == 0 Or ($ubt1 != 0 And $i <= SE_TNODE And $j <= SE_TNODE)) {
                if ($q > 1) {
                  if ($q == 2) {
                    $aspect = " blending with ";
                  } elseif ($q == 3 Or $q == 5) {
                    $aspect = " harmonizing with ";
                  } elseif ($q == 4 Or $q == 6) {
                    $aspect = " discordant to ";
                  }

                  $phrase_to_look_for = $pl_name[$i] . $aspect . $pl_name[$j];
                  $file = $services_path . "/natal_files/" . strtolower($pl_name[$i]) . ".txt";
                  $string = self::Find_Specific_Report_Paragraph_ASPECTS($phrase_to_look_for, $file, $i, $j, $p_h);
                  $string = nl2br($string);

                  $aspect_text[0][$num_aspects] = $p_h[$i][$j];
                  $aspect_text[1][$num_aspects] = $string;

                  $num_aspects++;

                  $this->planet_texts[$pl_name[$i]] .= "<font size='3'>" . $string . "</font>";
                  $this->planet_texts[$pl_name[$j]] .= "<font size='3'>" . $string . "</font>";
                }
              }
            }
          }


        }
      }

      //now sort the aspect interpretations according to power
      array_multisort($aspect_text[0], SORT_NUMERIC, SORT_DESC, $aspect_text[1], SORT_REGULAR);

      $p_aspect_interp = "";
      for ($i = 0; $i <= $num_aspects - 1; $i++) {
        $p_aspect_interp .= $aspect_text[1][$i];
      }

      $result .= "<font size='3'>" . $p_aspect_interp . "</font>";


      //display planet in sign interpretation
      //get header first
      $result .= "<center><font size='+1' color='#0000ff'><b>SIGN POSITIONS OF PLANETS</b></font></center>";

      $file = $services_path . "/natal_files/sign.txt";
      $fh = fopen($file, "r");
      $string = fread($fh, filesize($file));
      fclose($fh);

      $string = nl2br($string);
      $sign_interp = $string;

      // loop through each planet
      for ($i = 0; $i <= LAST_PLANET; $i++)            //was 6
      {
        $s_pos = floor($longitude1[$i] / 30) + 1;

        $deg = Composite::Reduce_below_30($longitude1[$i]);
        if ($ubt1 == 1 And $i == 1 And ($deg < 7.7 Or $deg > 22.3)) {
          continue;            //if the Moon is too close to the beginning or the end of a sign, then do not include it
        }
        $phrase_to_look_for = $pl_name[$i] . " in";
        $file = $services_path . "/natal_files/sign_" . trim($s_pos) . ".txt";
        $string = Composite::Find_Specific_Report_Paragraph($phrase_to_look_for, $file);
        $string = nl2br($string);
        $this->planet_texts[$pl_name[$i]] .= "<font size='3'>" . $string . "</font>";
        $this->sign_texts[$name_of_sign[$s_pos]] .= "<font size='3'>" . $string . "</font>";
        $sign_interp .= $string;
      }

      $result .= "<font size='3'>" . $sign_interp . "</font>";


      if ($ubt1 == 0) {
        //display planet in house interpretation
        //get header first
        $result .= "<center><font size='+1' color='#0000ff'><b>HOUSE POSITIONS OF PLANETS</b></font></center>";

        $file = $services_path . "/natal_files/house.txt";
        $fh = fopen($file, "r");
        $string = fread($fh, filesize($file));
        fclose($fh);

        $string = nl2br($string);
        $house_interp = $string;

        // loop through each planet
        for ($i = 0; $i <= LAST_PLANET; $i++)                //was 9
        {
          $h_pos = $house_pos1[$i];
          $phrase_to_look_for = $pl_name[$i] . " in";
          $file = $services_path . "/natal_files/house_" . trim($h_pos) . ".txt";
          $string = Composite::Find_Specific_Report_Paragraph($phrase_to_look_for, $file);
          $string = nl2br($string);
          $this->planet_texts[$pl_name[$i]] .= "<font size='3'>" . $string . "</font>";
          $house_interp .= $string;
        }

        $result .= "<font size='3'>" . $house_interp . "</font>";
      }


      $result .= "<center><font size='+1' color='#0000ff'><b>SABIAN SYMBOL POSITIONS OF PLANETS</b></font></center>";

      $file = $services_path . "/natal_files/sabian.txt";
      $fh = fopen($file, "r");
      $string = fread($fh, filesize($file));
      fclose($fh);

      $string = nl2br($string);
      $sign_interp = $string;

      // loop through each planet
      for ($i = 0; $i <= 9; $i++) {
        $s_pos = floor($longitude1[$i] / 30) + 1;

        $deg = floor(Composite::Reduce_below_30($longitude1[$i])) + 1;        //add 1 to degree
        if ($ubt1 == 1 And $i == 1) {
          continue;            //do not include the Moon for an unknown birth time
        }
        $phrase_to_look_for = trim($name_of_sign[$s_pos]) . " " . $deg;
        $file = $services_path . "/natal_files/sabian_" . trim($s_pos) . ".txt";

        $string = Composite::Find_Specific_Report_Paragraph($phrase_to_look_for, $file);
        $string = nl2br($string);
        $this->planet_texts[$pl_name[$i]] .= "<font size='3'>" . $pl_name[$i] . " in " . $string . "</font>";
        $this->sign_texts[$name_of_sign[$s_pos]] .= "<font size='3'>" . $pl_name[$i] . " in " . $string . "</font>";
        $sign_interp .= $pl_name[$i] . " in " . $string;
      }

      //Ascendant
      if ($ubt1 == 0) {
        $s_pos = floor($longitude1[LAST_PLANET + 1] / 30) + 1;

        $deg = floor(Composite::Reduce_below_30($longitude1[LAST_PLANET + 1])) + 1;        //add 1 to degree
        $phrase_to_look_for = trim($name_of_sign[$s_pos]) . " " . $deg;
        $file = $services_path . "/natal_files/sabian_" . trim($s_pos) . ".txt";

        $string = Composite::Find_Specific_Report_Paragraph($phrase_to_look_for, $file);
        $string = nl2br($string);
        $this->sign_texts[$name_of_sign[$s_pos]] .= "<font size='3'>Ascendant in " . $string . "</font>";
        $sign_interp .= "Ascendant in " . $string;
      }

      //MC
      if ($ubt1 == 0) {
        $s_pos = floor($longitude1[LAST_PLANET + 10] / 30) + 1;

        $deg = floor(Composite::Reduce_below_30($longitude1[LAST_PLANET + 10])) + 1;        //add 1 to degree
        $phrase_to_look_for = trim($name_of_sign[$s_pos]) . " " . $deg;
        $file = $services_path . "/natal_files/sabian_" . trim($s_pos) . ".txt";

        $string = Composite::Find_Specific_Report_Paragraph($phrase_to_look_for, $file);
        $string = nl2br($string);
        $this->sign_texts[$name_of_sign[$s_pos]] .= "<font size='3'>MC in " . $string . "</font>";
        $sign_interp .= "MC in " . $string;
      }

      $result .= "<font size='3'>" . $sign_interp . "</font>";


      //display closing
      $result .= "<br><center><font size='+1' color='#0000ff'><b>CLOSING COMMENTS</b></font></center>";

      if ($ubt1 == 1) {
        $file = $services_path . "/natal_files/closing_unk.txt";
      } else {
        $file = $services_path . "/natal_files/closing.txt";
      }
      $fh = fopen($file, "r");
      $string = fread($fh, filesize($file));
      fclose($fh);

      $closing = nl2br($string);
      $result .= "<font size='3'>" . $closing . "</font>";

      $result .= '</font></td></tr>';
      $result .= '</table></center>';
      $result .= "<br><br>";
    if (is_array($this->planet_explanations)) {
      foreach ($this->planet_explanations as &$plex)
        $plex = $this->planet_texts[$plex];
    }
    if (is_array($this->sign_explanations)) {
      foreach($this->sign_explanations as &$sex)
        $sex = $this->sign_texts[$sex];
    }
    $this->natal_explanation = $result;
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