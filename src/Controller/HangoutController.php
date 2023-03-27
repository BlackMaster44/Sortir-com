<?php

namespace App\Controller;

use App\Repository\HangoutRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route('hangout', name:'hangout_')]
class HangoutController extends AbstractController
{
    #[Route('/create', name: 'create')]
    public function createHangout(): Response
    {

        return $this->render('hangout/index.html.twig', [
            'controller_name' => 'HangoutController',
        ]);
    }

    #[Route('details/{id}', name: 'details')]
    public function details(int $id, HangoutRepository $hr): Response
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
