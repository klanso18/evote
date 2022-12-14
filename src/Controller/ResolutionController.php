<?php

namespace App\Controller;

use App\Entity\Campaign;
use App\Entity\Resolution;
use App\Form\ResolutionType;
use Symfony\Component\Uid\Uuid;
use App\Repository\ResolutionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

#[Route('/campaign', name: 'campaign_resolution_')]
class ResolutionController extends AbstractController
{
    #[Route('/{uuid}/resolution', name: 'index')]
    public function showResolution(
        Campaign $campaign,
        ResolutionRepository $resolutionRepository
    ): Response {
        $this->denyAccessUnlessGranted('view', $campaign);
        $resolution = $resolutionRepository->findAll();
        return $this->render('dashboard/resolution/index.html.twig', [
            'campaign' => $campaign,
            'resolution' => $resolution
        ]);
    }

    #[Route('/{uuid}/resolution/new', name: 'new')]
    public function new(
        Request $request,
        ResolutionRepository $resolutionRepository,
        Campaign $campaign,
    ): Response {
        $this->denyAccessUnlessGranted('view', $campaign);
        $resolution = new Resolution();
        $form = $this->createForm(ResolutionType::class, $resolution);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $resolution->setCampaign($campaign);
            $resolutionRepository->add($resolution, true);
            $uuid = Uuid::v4();
            $resolution->setUuid($uuid->toRfc4122());
            $resolutionRepository->add($resolution, true);
            $this->addFlash(
                'success',
                'La résolution ' . $resolution->getName() . ' a bien été ajoutée à la campagne ' . $campaign->getName()
            );
            return $this->redirectToRoute(
                'campaign_resolution_index',
                [
                    'uuid' => $campaign->getUuid()
                ],
                Response::HTTP_SEE_OTHER
            );
        }

        return $this->renderForm('dashboard/resolution/new.html.twig', [
            'form' => $form,
            'campaign' => $campaign,
        ]);
    }

    #[Route('/{campaign_uuid}/resolution/{resolution_uuid}/edit', name: 'edit', methods: ['GET', 'POST'])]
    #[ParamConverter('campaign', options: ['mapping' => ['campaign_uuid' => 'uuid']])]
    #[ParamConverter('resolution', options: ['mapping' => ['resolution_uuid' => 'uuid']])]
    public function edit(
        Request $request,
        Campaign $campaign,
        Resolution $resolution,
        ResolutionRepository $resolutionRepository
    ): Response {
        $this->denyAccessUnlessGranted('view', $campaign);
        $form = $this->createForm(ResolutionType::class, $resolution);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $resolutionRepository->add($resolution, true);
            $this->addFlash(
                'success',
                'La résolution ' . $resolution->getName() . ' a bien été modifiée'
            );
            return $this->redirectToRoute(
                'campaign_resolution_index',
                [
                    'uuid' => $campaign->getUuid()
                ],
                Response::HTTP_SEE_OTHER
            );
        }
        return $this->renderForm('dashboard/resolution/edit.html.twig', [
            'form' => $form,
            'resolution' => $resolution,
            'campaign' => $campaign
        ]);
    }

    #[Route('/{campaign_uuid}/resolution/{resolution_uuid}/delete', name: 'delete', methods: ['POST'])]
    #[ParamConverter('campaign', options: ['mapping' =>
    ['campaign_uuid' => 'uuid']])]
    #[ParamConverter('resolution', options: ['mapping' =>
    ['resolution_uuid' => 'uuid']])]
    public function delete(
        Request $request,
        Campaign $campaign,
        Resolution $resolution,
        ResolutionRepository $resolutionRepository
    ): Response {
        $this->denyAccessUnlessGranted('view', $campaign);
        if ($this->isCsrfTokenValid('delete' . $resolution->getUuid(), $request->request->get('_token'))) {
            $resolutionRepository->remove($resolution, true);
            $this->addFlash(
                'success',
                'La résolution ' . $resolution->getName() . ' a bien été supprimée de la campagne '
                . $campaign->getName()
            );
        }
        return $this->redirectToRoute('campaign_resolution_index', [
            'uuid' => $campaign->getUuid()
        ], Response::HTTP_SEE_OTHER);
    }
}
