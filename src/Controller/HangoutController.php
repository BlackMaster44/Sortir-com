<?php

namespace App\Controller;

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
}
