<?php

namespace App\DataFixtures;

use App\Entity\House;
use App\Entity\MeterReading;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker\Factory;

class AppFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     * 
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('en_GB');

        $numberOfHouses = 10;

        for ($i = 0; $i < $numberOfHouses; $i++) {
            $house = new House();
            $house->setPostcode($faker->postcode);

            $startDate = new \DateTime('2024-10-01 00:00:00');
            $endDate = new \DateTime('2024-11-01 00:00:00');
            $interval = new \DateInterval('PT1H');
            $period = new \DatePeriod($startDate, $interval, $endDate);

            $previousReading = 0;

            foreach ($period as $datetime) {
                $meterReading = new MeterReading();
                $meterReading->setTimestamp(clone $datetime);

                $nextReading = $previousReading + mt_rand(1, 5);
                $meterReading->setReading($nextReading);

                $meterReading->setHouse($house);

                $manager->persist($meterReading);

                $previousReading = $nextReading;
            }

            $manager->persist($house);
        }

        $manager->flush();
    }
}
