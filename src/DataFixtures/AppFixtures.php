<?php

namespace App\DataFixtures;

use App\Entity\House;
use App\Entity\Meter;
use App\Entity\MeterReading;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $meter = new Meter();

        $startDate = new \DateTime('2023-10-01 00:00:00');
        $endDate = new \DateTime('2023-11-01 00:00:00');
        $interval = new \DateInterval('PT1H');
        $period = new \DatePeriod($startDate, $interval, $endDate);

        $previousReading = 0;

        foreach($period as $datetime) {

            $meterReading = new MeterReading();
            $meterReading->setMeter($meter);
            $meterReading->setTimestamp(clone $datetime);

            $nextReading = $previousReading + mt_rand(1, 50);

            $meterReading->setReading($nextReading);

            $meter->addMeterReading($meterReading);

            $manager->persist($meterReading);
            $manager->persist($meter);

            $previousReading = $nextReading;
        }

        $house = new House();
        $house->setPostcode('DN14 0AR');
        $house->setMeter($meter);

        $manager->persist($house);
        $manager->flush();
    }
}
