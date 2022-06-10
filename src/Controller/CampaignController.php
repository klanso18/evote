<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\Campaign;
use App\Form\CampaignType;
use App\Repository\CompanyRepository;
use App\Repository\CampaignRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/campaign', name: 'campaign_')]
class CampaignController extends AbstractController
{
    #[Route('/new', name: 'new')]
    public function new(
        Request $request,
        CampaignRepository $campaignRepository,
        CompanyRepository $companyRepository
    ): Response {
        $campaign = new Campaign();

        $form = $this->createForm(CampaignType::class, $campaign);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $companyName = $form->get('company')->getData();
            if (!empty($companyName)) {
                $company = $companyRepository->findOneByName($companyName);
                if (!$company) {
                    $company = new Company();
                    $company->setName($companyName);
                }
                $campaign->setCompany($company);
            }
            $uuid = Uuid::v4();
            $campaign->setUuid($uuid->toRfc4122());
            $campaignRepository->add($campaign, true);
            return $this->redirectToRoute('campaign_new');
        }

        return $this->renderForm('campaign/new.html.twig', [
            'form' => $form,
        ]);
    }
}
