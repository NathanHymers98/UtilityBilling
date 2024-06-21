<?php

namespace App\Controller\Bill;

use App\Service\BillingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BillController extends AbstractController
{
    /**
     * @param  BillingService $billingService
     * @param  RequestStack $requestStack
     */
    public function __construct(private BillingService $billingService, private RequestStack $requestStack)
    {
        
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

        return $this->render('bill/calculate_bills.html.twig', [
            'results' => $results,
        ]);
    }
}
