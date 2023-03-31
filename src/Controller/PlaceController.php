<?php

namespace App\Controller;

use App\Entity\Place;
use App\Form\PlaceType;
use App\Repository\PlaceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('create', name: 'create')]
    public function create(Request $request,
                           EntityManagerInterface $emi): Response
    {

        $place = new Place();
        $placeForm = $this->createForm(PlaceType::class, $place);
        $placeForm->handleRequest($request);

        if ($placeForm->isSubmitted() && $placeForm->isValid()) {
            $emi->persist($place);
            $emi->flush();

            $this->addFlash('success flash', 'Place successfully added');

            return $this->redirectToRoute('place_list');
        }

        return $this->render('place/create.html.twig', [
            'placeForm'=>$placeForm
        ]);
    }

    #[Route('update/{idPlace}', name: 'update')]
    public function update(Request $request,
                           int $idPlace,
                           PlaceRepository $pr,
                           EntityManagerInterface $emi): Response {

        $place = $pr->find($idPlace);
        $placeForm = $this->createForm(PlaceType::class, $place);
        $placeForm->handleRequest($request);

        if($placeForm->isSubmitted() && $placeForm->isValid()) {
            $emi->flush();
            $this->addFlash('success flash', 'Place successfully updated');

            return $this->redirectToRoute('place_list');
        }

        return $this->render('place/create.html.twig', [
            'placeForm'=>$placeForm
        ]);
    }

    #[Route('delete/{idPlace}', name: 'delete')]
    public function delete(int $idPlace,
                           PlaceRepository $pr,
                           EntityManagerInterface $emi,
                           Request $request): Response {

        $place = $pr->find($idPlace);
        $emi->remove($place);
        $emi->flush();

        return $this->redirectToRoute('place_list');
    }
}
