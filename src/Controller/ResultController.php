<?php

namespace App\Controller;

use App\Entity\Campaign;
use App\Entity\Resolution;
use App\Services\ChartResults;
use App\Repository\VoteRepository;
use Symfony\UX\Chartjs\Model\Chart;
use App\Repository\CampaignRepository;
use Doctrine\Common\Collections\Collection;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

#[Route('/results', name: 'results_')]
class ResultController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(
        CampaignRepository $campaignRepository,
        PaginatorInterface $paginator,
        Request $request,
    ): Response {
        $query = $campaignRepository->queryAllActive();
        $pagination = $paginator->paginate($query, $request->query->getInt('page', 1), 10);
        return $this->render('dashboard/campaign/results/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/{uuid}/show', name: 'show')]
    public function showResults(
        Campaign $campaign,
        ChartResults $chartResults,
    ): Response {
        $this->denyAccessUnlessGranted('view', $campaign);
        if ($campaign->getHasCollege()) {
            $resolutionsCharts = $chartResults->getResultByCollege(
                $campaign->getResolutions()
            );
        } else {
            //créer le service pour afficher les votes des utilisateurs qui ne sont pas associés à un collège
            $resolutionsCharts = $chartResults->getResultByVoter(
                $campaign->getResolutions()
            );
        }

        return $this->render('dashboard/campaign/results/show.html.twig', [
            'campaign' => $campaign,
            'resolutions' => $resolutionsCharts,
        ]);
    }
}
