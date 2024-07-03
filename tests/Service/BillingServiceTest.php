<?php

namespace App\Tests\Service;

use App\Entity\House;
use App\Entity\MeterReading;
use App\Service\BillingService;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;
use App\Repository\HouseRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class BillingServiceTest extends TestCase
{
    /**
     * @param array $houses
     * @param array $rates
     * @param array $expectedCosts
     * 
     * @dataProvider calculateBillsDataProvider
     * 
     * @return void
     */
    public function testCalculateBills(array $houses, array $rates, array $expectedCosts): void
    {
        $houseRepository = $this->createConfiguredMock(
            HouseRepository::class,
            [
                'findAll' => $houses
            ]
        );

        $entityManager = $this->createConfiguredMock(
            EntityManager::class,
            [
                'getRepository' => $houseRepository
            ]
        );

        $session = $this->createConfiguredMock(
            SessionInterface::class,
            [
                'get' => $rates
            ]
        );

        $requestStack = $this->createConfiguredMock(
            RequestStack::class,
            [
                'getSession' => $session
            ]
        );

        $billingService = new BillingService($entityManager, $requestStack);

        $results = $billingService->calculateBills($session);

        self::assertCount(count($houses), $results);

        foreach ($results as $index => $result) {
            self::assertSame($houses[$index], $result['house']);
            self::assertEquals($expectedCosts[$index], $result['totalCost']);
        }
    }

    /**
     * @return iterable
     */
    public function calculateBillsDataProvider(): iterable
    {    
        yield 'single house, multiple readings, only peak' => [
            'houses' => [
                $this->createHouseMock([
                    ['meter_id' => 1, 'timestamp' => '2024-10-10 08:00:00', 'reading' => 100],
                    ['meter_id' => 1, 'timestamp' => '2024-10-10 09:00:00', 'reading' => 153],
                    ['meter_id' => 1, 'timestamp' => '2024-10-10 10:00:00', 'reading' => 158],
                    ['meter_id' => 1, 'timestamp' => '2024-10-10 11:00:00', 'reading' => 163]
                ])
            ],
            'rates' => ['peakRate' => 0.2, 'offPeakRate' => 0.1],
            'expectedCosts' => [32.6]
        ];

        yield 'multiple houses, multiple readings, only peak' => [
            'houses' => [
                $this->createHouseMock([
                    ['meter_id' => 1, 'timestamp' => '2024-10-10 08:00:00', 'reading' => 100],
                    ['meter_id' => 1, 'timestamp' => '2024-10-10 09:00:00', 'reading' => 153],
                    ['meter_id' => 1, 'timestamp' => '2024-10-10 10:00:00', 'reading' => 158],
                    ['meter_id' => 1, 'timestamp' => '2024-10-10 11:00:00', 'reading' => 163]
                ]),
                $this->createHouseMock([
                    ['meter_id' => 2, 'timestamp' => '2024-10-10 08:00:00', 'reading' => 200],
                    ['meter_id' => 2, 'timestamp' => '2024-10-10 09:00:00', 'reading' => 250],
                    ['meter_id' => 2, 'timestamp' => '2024-10-10 10:00:00', 'reading' => 275],
                    ['meter_id' => 2, 'timestamp' => '2024-10-10 11:00:00', 'reading' => 280]
                ])
            ],
            'rates' => ['peakRate' => 0.2, 'offPeakRate' => 0.1],
            'expectedCosts' => [32.6, 56.0]
        ];

        yield 'single house, multiple readings, only off-peak' => [
            'houses' => [
                $this->createHouseMock([
                    ['meter_id' => 1, 'timestamp' => '2024-10-10 01:00:00', 'reading' => 100],
                    ['meter_id' => 1, 'timestamp' => '2024-10-10 02:00:00', 'reading' => 153],
                    ['meter_id' => 1, 'timestamp' => '2024-10-10 03:00:00', 'reading' => 158],
                    ['meter_id' => 1, 'timestamp' => '2024-10-10 04:00:00', 'reading' => 163]
                ])
            ],
            'rates' => ['peakRate' => 0.2, 'offPeakRate' => 0.1],
            'expectedCosts' => [16.3]
        ];

        yield 'multiple houses, multiple readings, only off-peak' => [
            'houses' => [
                $this->createHouseMock([
                    ['meter_id' => 1, 'timestamp' => '2024-10-10 01:00:00', 'reading' => 100],
                    ['meter_id' => 1, 'timestamp' => '2024-10-10 02:00:00', 'reading' => 153],
                    ['meter_id' => 1, 'timestamp' => '2024-10-10 03:00:00', 'reading' => 158],
                    ['meter_id' => 1, 'timestamp' => '2024-10-10 04:00:00', 'reading' => 163]
                ]),
                $this->createHouseMock([
                    ['meter_id' => 2, 'timestamp' => '2024-10-10 01:00:00', 'reading' => 200],
                    ['meter_id' => 2, 'timestamp' => '2024-10-10 02:00:00', 'reading' => 250],
                    ['meter_id' => 2, 'timestamp' => '2024-10-10 03:00:00', 'reading' => 275],
                    ['meter_id' => 2, 'timestamp' => '2024-10-10 04:00:00', 'reading' => 280]
                ])
            ],
            'rates' => ['peakRate' => 0.2, 'offPeakRate' => 0.1],
            'expectedCosts' => [16.3, 28.0]
        ];

        yield 'multiple houses, multiple readings, mix of peak and off-peak' => [
            'houses' => [
                $this->createHouseMock([
                    ['meter_id' => 1, 'timestamp' => '2024-10-10 08:00:00', 'reading' => 100],
                    ['meter_id' => 1, 'timestamp' => '2024-10-10 09:00:00', 'reading' => 153],
                    ['meter_id' => 1, 'timestamp' => '2024-10-10 10:00:00', 'reading' => 158],
                    ['meter_id' => 1, 'timestamp' => '2024-10-10 11:00:00', 'reading' => 163]
                ]),
                $this->createHouseMock([
                    ['meter_id' => 2, 'timestamp' => '2024-10-10 01:00:00', 'reading' => 200],
                    ['meter_id' => 2, 'timestamp' => '2024-10-10 02:00:00', 'reading' => 250],
                    ['meter_id' => 2, 'timestamp' => '2024-10-10 03:00:00', 'reading' => 275],
                    ['meter_id' => 2, 'timestamp' => '2024-10-10 04:00:00', 'reading' => 280]
                ])
            ],
            'rates' => ['peakRate' => 0.2, 'offPeakRate' => 0.1],
            'expectedCosts' => [32.6, 28.0]
        ];
    }

    /**
     * @param int $hour
     * @param array $rates
     * @param float $expectedRate
     * 
     * @dataProvider getRateForHourDataProvider
     * 
     * @return void
     */
    public function testGetRateForHour(int $hour, array $rates, float $expectedRate): void
    {
        $billingService = new BillingService($this->createMock(EntityManager::class), $this->createMock(RequestStack::class));

        $reflection = new \ReflectionClass(BillingService::class);
        $method = $reflection->getMethod('getRateForHour');
        $method->setAccessible(true);

        self::assertEquals($expectedRate, $method->invokeArgs($billingService, [$hour, $rates]));
    }

    /**
     * @return iterable
     */
    public function getRateForHourDataProvider(): iterable
    {
        yield 'peak hour' => [
            'hour' => 10,
            'rates' => [
                    'peakRate' => 0.2,
                    'offPeakRate' => 0.1
            ],
            'expectedRate' => 0.2
        ];

        yield 'off-peak hour' => [
            'hour' => 2,
            'rates' => [
                    'peakRate' => 0.2,
                    'offPeakRate' => 0.1
            ],
            'expectedRate' => 0.1
        ];

        yield 'boundary peak start' => [
            'hour' => 7,
            'rates' => [
                    'peakRate' => 0.2,
                    'offPeakRate' => 0.1
            ],
            'expectedRate' => 0.2
        ];

        yield 'boundary peak end' => [
            'hour' => 23,
            'rates' => [
                    'peakRate' => 0.2,
                    'offPeakRate' => 0.1
            ],
            'expectedRate' => 0.2
        ];

        yield 'boundary off-peak start' => [
            'hour' => 0,
            'rates' => [
                'peakRate' => 0.2,
                'offPeakRate' => 0.1
            ], 
            'expectedRate' => 0.1
        ];

        yield 'boundary off-peak end' => [
            'hour' => 6,
            'rates' => [
                'peakRate' => 0.2,
                'offPeakRate' => 0.1
            ], 
            'expectedRate' => 0.1];
    }

    /**
     * @param array $readingsData
     * 
     * @return House
     */
    private function createHouseMock(array $readingsData): House
    {
        $house = new House();
        
        foreach ($readingsData as $readingData) {
            $reading = new MeterReading();
            $reading->setMeterId($readingData['meter_id']);
            $reading->setTimestamp(new \DateTime($readingData['timestamp']));
            $reading->setReading($readingData['reading']);
            $house->addMeterReading($reading);
        }
        return $house;
    }
}
