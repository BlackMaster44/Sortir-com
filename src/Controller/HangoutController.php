<?php

namespace App\Controller;

use App\Repository\HangoutRepository;
use ContainerC63iavh\getHangoutRepositoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/hangout', name: 'hangout_')]
class HangoutController extends AbstractController
{
    #[Route('/list', name: 'list')]
    public function index(HangoutRepository $hr): Response
    {
        $hangouts = $hr->findAll();

        return $this->render('hangout/list.html.twig', [
            'hangouts' => $hangouts
        ]);
    }

    #[Route('details/{id}', name: 'details')]
    public function details(int $id,
                            getHangoutRepositoryService $hr): Response
    {

        $hangout = $hr->find($id);

        return $this->render('hangout/details.html.twig', [
            'hangout' => $hangout
        ]);
    }

    #[Route('/goingTo', name: 'goingTo')]
    public function goingTo() {

        return $this->redirectToRoute('hangout_list', []);
    }

    #[Route('/notGoingAnymore', name: 'notGoingAnymore')]
    public function notGoingAnymore () {

        return $this->redirectToRoute('hangout_list', []);
    }
}
