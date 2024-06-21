<?php

namespace App\Tests\Entity;

use App\Entity\House;
use App\Entity\MeterReading;
use PHPUnit\Framework\TestCase;

class HouseTest extends TestCase
{
    /**
     * @return void
     */
    public function testGetSetPostcode(): void
    {
        $house = new House();
        $postcode = 'DN14 0AR';
        $house->setPostcode($postcode);
        
        self::assertEquals($postcode, $house->getPostcode());
    }

    /**
     * @return void
     */
    public function testAddMeterReading(): void
    {
        $house = new House();
        $meterReading = new MeterReading();

        $house->addMeterReading($meterReading);

        self::assertCount(1, $house->getMeterReadings());
        self::assertTrue($house->getMeterReadings()->contains($meterReading));
        self::assertSame($house, $meterReading->getHouse());
    }

    /**
     * @return void
     */
    public function testRemoveMeterReading(): void
    {
        $house = new House();
        $meterReading = new MeterReading();

        $house->addMeterReading($meterReading);
        self::assertCount(1, $house->getMeterReadings());

        $house->removeMeterReading($meterReading);
        
        self::assertCount(0, $house->getMeterReadings());
        self::assertFalse($house->getMeterReadings()->contains($meterReading));
        self::assertNull($meterReading->getHouse());
    }
}