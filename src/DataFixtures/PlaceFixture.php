<?php

namespace App\DataFixtures;

use App\Entity\Place;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PlaceFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        if (($handle = fopen('src/Services/worldcities.csv', "r")) !== FALSE) {
            if (fgetcsv($handle) !== FALSE) {
                while (($data = fgetcsv($handle)) !== FALSE) {
                    $place = $this->getCity($data);
                    if ($place)
                        $manager->persist($place);
                }
                $manager->flush();
            }
            fclose($handle);
        }
    }

    private function getCity($data)
    {
        if (count($data) > 4 && abs($data[2]) < 66) {
            $place = new Place();
            $placename = (strlen($data[4]) > 30 ? substr($data[4], 0, 30) : $data[4]) . ', ' . (strlen($data[0]) > 30 ? substr($data[0], 0, 30) : $data[0]);
            $place->setPlaceName($placename);
            $place->setLatitude($this->turnToString("NS", $data[2]));
            $place->setLongitude($this->turnToString("EW", $data[3]));
            return $place;
        }
        return null;
    }
    
    public function turnToString($plusMinusStr, $bcoord, $toCalcBack = false)
    {
        $result = $plusMinusStr[$bcoord[0] == '-' ? 1 : 0];
        if ($bcoord[0] == '-')
            $coord = floatval(substr($bcoord, 1));
        else
            $coord = floatval($bcoord);
        $tr = floor($coord);
        if ($plusMinusStr == "EW" && $tr < 100)
            $result .= '0';
        $result .= ($tr > 9 ? '' : '0') . $tr . '.';
        $coord -= $tr;
        $coord *= 60;
        $tr = floor($coord);
        $result .= ($tr > 9 ? '' : '0') . $tr . '.';
        $coord -= $tr;
        $coord *= 60;
        $tr = round($coord);
        if ($tr == 60) {
            $result = $this->turnToString($plusMinusStr, strval(($bcoord[0] == "-" ? floatval(substr($bcoord, 1)) : floatval($bcoord)) + 0.0025), true);
        }
        else {
            if ($toCalcBack)
                $tr -= 9;
            $result .= ($tr > 9 ? '' : '0') . $tr;
        }
        return $result;
    }
}
