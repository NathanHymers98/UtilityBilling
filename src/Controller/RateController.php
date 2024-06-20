<?php

namespace App\Controller;

use App\Form\RateType;
use App\Service\BillingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RateController extends AbstractController
{
    public function __construct(private BillingService $billingService, private RequestStack $requestStack)
    {
        
    }


    #[Route('/input-rates', name: 'input_rates')]
    public function inputRates(Request $request): Response
    {
        $session = $this->requestStack->getSession();

        $form = $this->createForm(RateType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rateData = $form->getData();

            $rates = [
                'peakRate' => $rateData['peakRate'] / 100,
                'offPeakRate' => $rateData['offPeakRate'] / 100,
            ];

            $session->set('rates', $rates);

            $this->addFlash('success', 'Rates have been set.');

            return $this->redirectToRoute('input_rates');
        }

        return $this->render('rate/input_rates.html.twig', [
            'rateForm' => $form->createView(),
        ]);
    }

    #[Route('/calculate-bills', name: 'calculate_bills')]
    public function calculateBills(): Response
    {
        $session = $this->requestStack->getSession();

        if (!$session->has('rates')) {
            return $this->redirectToRoute('input_rates');
        }

        $results = $this->billingService->calculateBills();

        $session->clear();

        return $this->render('rate/calculate_bills.html.twig', [
            'results' => $results,
        ]);
    }
}
