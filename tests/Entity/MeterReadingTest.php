<?php

namespace App\Tests\Entity;

use App\Entity\House;
use App\Entity\MeterReading;
use PHPUnit\Framework\TestCase;

class MeterReadingTest extends TestCase
{
    public function testGetSetTimestamp(): void
    {
        $meterReading = new MeterReading();
        $timestamp = new \DateTime('2024-10-10 08:00:00');
        $meterReading->setTimestamp($timestamp);
        
        self::assertEquals($timestamp, $meterReading->getTimestamp());
    }

    public function testGetSetReading(): void
    {
        $meterReading = new MeterReading();
        $reading = 100;
        $meterReading->setReading($reading);
        
        self::assertEquals($reading, $meterReading->getReading());
    }

    public function testGetSetHouse(): void
    {
        $meterReading = new MeterReading();
        $house = new House();
        $meterReading->setHouse($house);
        
        self::assertSame($house, $meterReading->getHouse());
    }

    public function testGetId(): void
    {
        $meterReading = new MeterReading();
        
        $reflection = new \ReflectionClass($meterReading);
        $property = $reflection->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($meterReading, 1);

        self::assertEquals(1, $meterReading->getId());
    }
}