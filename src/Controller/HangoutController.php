<?php

namespace App\Controller;

use App\Repository\HangoutRepository;
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
}
