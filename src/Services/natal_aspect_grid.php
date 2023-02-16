<?php

namespace App\Controller;

use App\Controller\Natal;
use App\Controller\Natal_wheel;

class Natal_aspect_grid
{
  public static function getImage($rx1, $p1, $hc1, $ubt1, Natal &$natal, $personId = null)
  {

    include("constants_eng.php");

    $rx1 = safeEscapeString($rx1);

    $ubt1 = intval(safeEscapeString($ubt1));

    $longitude = unserialize($p1);
    $hc1 = unserialize($hc1);

    $longitude[LAST_PLANET + 1] = $hc1[1];
    $longitude[LAST_PLANET + 2] = $hc1[10];


    $height_cut_down = 0;
    if ($ubt1 != 0) {
      $height_cut_down = 115;
    }

    ob_clean();
    ob_start();

// create the blank image
    $overall_size = 450;
    $extra_width = 255;   //in order to make total width = 680
    $margins = 20;      //left and right margins on the background graphic
    $margin_cell_line = 5;  //bottom line margin for planets above table cells

    $im = @imagecreatetruecolor($overall_size + $extra_width, $overall_size - $height_cut_down) or die("Cannot initialize new GD image stream");

// specify the colors
    $white = imagecolorallocate($im, 255, 255, 255);
    $red = imagecolorallocate($im, 255, 0, 0);
    $blue = imagecolorallocate($im, 0, 0, 255);
    $magenta = imagecolorallocate($im, 255, 0, 255);
    $yellow = imagecolorallocate($im, 255, 255, 0);
    $cyan = imagecolorallocate($im, 0, 255, 255);
    $green = imagecolorallocate($im, 0, 150, 0);
    $grey = imagecolorallocate($im, 127, 127, 127);
    $black = imagecolorallocate($im, 0, 0, 0);
    $lavender = imagecolorallocate($im, 160, 0, 255);
    $orange = imagecolorallocate($im, 255, 127, 0);
    $light_blue = imagecolorallocate($im, 239, 255, 255);
    $another_blue = imagecolorallocate($im, 0, 239, 239);
    $violet = imagecolorallocate($im, 135, 0, 255);

    imagecolortransparent($im, $white);

    $pl_name[0] = "Sun";
    $pl_name[1] = "Moon";
    $pl_name[2] = "Mercury";
    $pl_name[3] = "Venus";
    $pl_name[4] = "Mars";
    $pl_name[5] = "Jupiter";
    $pl_name[6] = "Saturn";
    $pl_name[7] = "Uranus";
    $pl_name[8] = "Neptune";
    $pl_name[9] = "Pluto";
    $pl_name[10] = "Chiron";
    $pl_name[11] = "Lilith";    //add a planet
    $pl_name[12] = "Node";
    $pl_name[13] = "Part of Fortune";
    $pl_name[14] = "Vertex";
    $pl_name[15] = "Ascendant";
    $pl_name[16] = "Midheaven";

    
    $pl_glyph[0] = 81;
    $pl_glyph[1] = 87;
    $pl_glyph[2] = 69;
    $pl_glyph[3] = 82;
    $pl_glyph[4] = 84;
    $pl_glyph[5] = 89;
    $pl_glyph[6] = 85;
    $pl_glyph[7] = 73;
    $pl_glyph[8] = 79;
    $pl_glyph[9] = 80;
    $pl_glyph[10] = 77;
    $pl_glyph[11] = 96;   //add a planet
    $pl_glyph[12] = 141;
    $pl_glyph[13] = 60;
    $pl_glyph[14] = 109;    //Vertex
    $pl_glyph[15] = 90;   //Ascendant
    $pl_glyph[16] = 88;   //Midheaven

    $sign_glyph[1] = 97;
    $sign_glyph[2] = 115;
    $sign_glyph[3] = 100;
    $sign_glyph[4] = 102;
    $sign_glyph[5] = 103;
    $sign_glyph[6] = 104;
    $sign_glyph[7] = 106;
    $sign_glyph[8] = 107;
    $sign_glyph[9] = 108;
    $sign_glyph[10] = 122;
    $sign_glyph[11] = 120;
    $sign_glyph[12] = 99;

    $cell_width = 25;
    $cell_height = 25;

    $last_planet_num = LAST_PLANET;
    
    $natal->planet_explanations = array();
    for ($i = 0; $i <= LAST_PLANET; ++$i)
      $natal->planet_explanations[] = $pl_name[$i];
    
    $num_planets = $last_planet_num + 1;

    $left_margin_planet_table = ($num_planets + 0.5) * $cell_width;

    if ($ubt1 == 0) {
      $number_to_use = $last_planet_num;
    } else {
      $number_to_use = $last_planet_num - 4;
    }


// ------------------------------------------

// create rectangle on blank image
    imagefilledrectangle($im, 0, 0, $overall_size + $extra_width, $overall_size - $height_cut_down, $white);    //705 x 450 - add a planet

    if (!realpath('../src/Services/arial.ttf'))
      $services_path = './src/Services';
    else
      $services_path = '../src/Services';
// MUST BE HERE - I DO NOT KNOW WHY - MAYBE TO PRIME THE PUMP
    imagettftext($im, 10, 0, 0, 0, $black, $services_path . '/arial.ttf', " ");

// ------------------------------------------


// draw the grid - horizontal lines
    for ($i = 0; $i <= $number_to_use - 1; $i++) {
      imageline($im, $margins, $cell_height * ($i + 1), $margins + $cell_width * ($i + 1), $cell_height * ($i + 1), $black);
    }


    if ($ubt1 == 0) {
      imageline($im, $margins, $cell_height * $num_planets, $margins + $cell_width * $i, $cell_height * $num_planets, $black);
    } else {
      imageline($im, $margins, $cell_height * ($num_planets - 4), $margins + $cell_width * $i, $cell_height * ($num_planets - 4), $black);
    }


// draw the grid - vertical lines
    for ($i = 1; $i <= $number_to_use; $i++) {
      if ($ubt1 == 0) {
        imageline($im, $margins + $cell_width * $i, $cell_height * $num_planets, $margins + $cell_width * $i, $cell_height * $i, $black);
      } else {
        imageline($im, $margins + $cell_width * $i, $cell_height * ($num_planets - 4), $margins + $cell_width * $i, $cell_height * $i, $black);
      }
    }


    if ($ubt1 == 0) {
      imageline($im, $margins, $cell_height * $num_planets, $margins, $cell_height, $black);
    } else {
      imageline($im, $margins, $cell_height * ($num_planets - 4), $margins, $cell_height, $black);
    }


// ------------------------------------------

// draw in the planet glyphs
    $natal->sign_explanations = array();
    $natal->offset_x_sign_explanation_border = $margins + $left_margin_planet_table + $cell_width * 5;
    for ($i = 0; $i <= LAST_PLANET; $i++) {
      if ($ubt1 == 0 Or ($ubt1 != 0 And $i <= SE_TNODE)) {
        Natal_wheel::drawboldtext($im, 14, 0, $margins + $i * $cell_width + $margin_cell_line, $cell_height * ($i + 1) - $margin_cell_line, $black, $services_path . '/HamburgSymbols.ttf', chr($pl_glyph[$i]), 0);

        // display planet data in the right-hand table
        Natal_wheel::drawboldtext($im, 16, 0, $margins + $left_margin_planet_table + $cell_width, $cell_height * ($i + 1), $black, $services_path . '/HamburgSymbols.ttf', chr($pl_glyph[$i]), 0);
        imagettftext($im, 10, 0, $margins + $left_margin_planet_table + $cell_width * 2, $cell_height * ($i + 1) - 3, $blue, $services_path . '/arial.ttf', $i == 13 ? "P. of Fortune" : $pl_name[$i]);
        $sign_num = floor($longitude[$i] / 30) + 1;
        $natal->sign_explanations[] = $name_of_sign[$sign_num];
        Natal_wheel::drawboldtext($im, 14, 0, $margins + $left_margin_planet_table + $cell_width * 5, $cell_height * ($i + 1), $black, $services_path . '/HamburgSymbols.ttf', chr($sign_glyph[$sign_num]), 0);

        imagettftext($im, 10, 0, $margins + $left_margin_planet_table + $cell_width * 6, $cell_height * ($i + 1) - 3, $blue, $services_path . '/arial.ttf', Composite::Convert_Longitude($longitude[$i]) . " " . $rx1[$i]);
      }
    }

// ------------------------------------------

// display the aspect glyphs in the aspect grid
    $aspects = Composite::GetAspectsArray();

// draw in the aspect lines
    for ($i = 0; $i <= $last_planet_num - 1; $i++) {
      for ($j = $i + 1; $j <= $last_planet_num; $j++) {
        $da = Abs($longitude[$i] - $longitude[$j]);
        if ($da > 180) {
          $da = 360 - $da;
        }

        for($q = 1; $q <= count($aspects); $q++) {
          $orb = $aspects[$q]->orb;
          if ($i == SE_SUN)
            $orb = $aspects[$q]->orb_for_sun;
          elseif ($i == SE_MOON)
            $orb = $aspects[$q]->orb_for_moon;
          if ($da <= $aspects[$q]->degrees + $orb And $da >= $aspects[$q]->degrees - $orb) {
            if ($ubt1 == 0 Or ($ubt1 != 0 And $i <= SE_TNODE And $j <= SE_TNODE)) {
              if ($aspects[$q]->glyph == 110)
                Natal_wheel::drawboldtext($im, 10, 0, $margins + $cell_width * ($i + 0.20), $cell_height * ($j + 1 - 0.20),
                    ${$aspects[$q]->color}, $services_path . '/arial.ttf', "bQ", 0);
              else
                Natal_wheel::drawboldtext($im, 14, 0, $margins + $cell_width * ($i + 0.20), $cell_height * ($j + 1 - 0.20),
                    ${$aspects[$q]->color}, $services_path . '/HamburgSymbols.ttf', chr($aspects[$q]->glyph), 0);
            }
          }
        }
      }
    }


    // draw the image in png format - using imagepng() results in clearer text compared with imagejpeg()
    imagepng($im);
    imagedestroy($im);
    $result = ob_get_contents();
    ob_end_clean();
    $file_path = 'natal_aspect_grid' . (empty($personId) ? '' : $personId) . '.png';
    file_put_contents($file_path, $result);
    return $file_path;
  }
}
?>