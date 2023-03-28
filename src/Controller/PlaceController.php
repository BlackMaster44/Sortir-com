<?php

namespace App\Controller;

use App\Repository\PlaceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/place', name: 'place_')]
class PlaceController extends AbstractController
{
    #[Route('/list', name: 'list')]
    public function list(PlaceRepository $pr): Response
    {
        $places = $pr->findAll();


        return $this->render('place/list.html.twig', [
            'places' => $places,
        ]);
    }
}
