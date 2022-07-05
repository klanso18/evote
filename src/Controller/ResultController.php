<?php

namespace App\Controller;

use App\Repository\CampaignRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/results', name: 'results_')]
class ResultController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(CampaignRepository $campaignRepository): Response
    {
        $campaigns = $campaignRepository->findAll();
        return $this->render('dashboard/campaign/result/index.html.twig', [
            'campaigns' => $campaigns,
        ]);
    }

    #[Route('/', name: 'show')]
    public function showResults(CampaignRepository $campaignRepository): Response
    {
        $results = $campaignRepository->findAll();
        return $this->render('dashboard/campaign/result/show.html.twig', [
        'results' => $results
        ]);
    }
}
