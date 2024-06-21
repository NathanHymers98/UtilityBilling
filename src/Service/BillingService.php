<?php

namespace App\Service;

use App\Entity\House;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\RequestStack;

class BillingService
{
    /**
     * @param  EntityManager $entityManager
     * @param  RequestStack $requestStack
     */
    public function __construct(private EntityManager $entityManager, private RequestStack $requestStack)
    {

    }

    /**
     * @return array
     */
    public function calculateBills(): array
    {
        $session = $this->requestStack->getSession();

        $houses = $this->entityManager->getRepository(House::class)->findAll();
        $results = [];
        $rates = $session->get('rates');

        foreach ($houses as $house) {
            $readings = $house->getMeterReadings();
    
            $totalCost = 0;
            $previousReading = 0;
            foreach ($readings as $reading) {
                $hour = (int)$reading->getTimestamp()->format('G');
                $rate = $this->getRateForHour($hour, $rates);

                if (0 !== $previousReading) {
                    $difference = $reading->getReading() - $previousReading;
                    $totalCost += $difference * $rate;
                } else {
                    $totalCost += $reading->getReading() * $rate;
                }
    
                $previousReading = $reading->getReading();
            }

            $totalCost = round($totalCost, 2);

            $results[] = [
                'house' => $house,
                'totalCost' => $totalCost
            ];
        }

        return $results;
    }

    /**
     * @param int $hour
     * @param array $rates
     * 
     * @return float
     */
    private function getRateForHour(int $hour, array $rates): float
    {
        if ($hour >= 7 && $hour < 24) { // Peak hours from 7 AM to midnight
            return $rates['peakRate'];
        } else { // Off-peak hours from midnight to 7 AM
            return $rates['offPeakRate'];
        }
    }
}