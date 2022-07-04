<?php

namespace App\Controller;

use App\Repository\CampaignRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/profile/results', name: 'results_')]
class ResultController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(CampaignRepository $campaignRepository): Response
    {
        $campaigns = $campaignRepository->findAll();
        return $this->render('dashboard/campaign/results/index.html.twig', [
            'campaigns' => $campaigns,
        ]);
    }
}
