<?php

namespace App\Controller;

use App\Entity\City;
use App\Form\CityType;
use App\Repository\CityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/city', name: 'city_')]
class CityController extends AbstractController
{
    #[Route('/list', name: 'list')]
    public function list(CityRepository $cr): Response
    {
           $cities = $cr->findAll();


        return $this->render('city/list.html.twig', [
            'cities'=>$cities
        ]);
    }

    #[Route('/create', name: 'create')]
    public function create(Request $request,
                           EntityManagerInterface $emi): Response {

        $city = new City();
        $cityFrom = $this->createForm(CityType::class, $city);
        $cityFrom->handleRequest($request);

        if($cityFrom->isSubmitted() && $cityFrom->isValid()) {
            $emi->persist($city);
            $emi->flush();

            $this->addFlash('success', 'City successfully added');

            return $this->redirectToRoute('city_list');
        }

        return $this->render('city/create.html.twig', [
            'cityForm'=>$cityFrom
        ]);
    }

    #[Route('/update{idCity}', name: 'update')]
    public function update(Request $request,
                           CityRepository $cr,
                           EntityManagerInterface $emi,
                           int $idCity): Response {

        $city = $cr->find($idCity);
        $cityForm = $this->createForm(CityType::class, $city);
        $cityForm->handleRequest($request);

        if($cityForm->isSubmitted() && $cityForm->isValid()) {
            $emi->flush();
            $this->addFlash('success', 'City successfully updated');

            return $this->redirectToRoute('city_list');
        }

        return $this->render('city/create.html.twig', [
           'cityForm'=>$cityForm
        ]);
    }

    #[Route('/delete{idCity}', name: 'delete')]
    public function delete(int $idCity,
                           CityRepository $cr,
                           EntityManagerInterface $emi,
                           Request $request) {

        $city = $cr->find($idCity);
        $emi->remove($city);
        $emi->flush();

        return $this->redirectToRoute('city_list');
    }
}
