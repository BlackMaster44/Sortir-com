<?php

namespace App\Controller;

use App\Entity\Hangout;

use App\Form\CreateHangoutType;

use App\Repository\HangoutRepository;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route('hangout', name:'hangout_')]
class HangoutController extends AbstractController
{
    #[Route('/create', name: 'create')]
    public function createHangout(Request $request, EntityManagerInterface $entityManager): Response
    {
        $hangout = new Hangout();
        $hangoutForm = $this->createForm(CreateHangoutType::class, $hangout);
        $hangoutForm->handleRequest($request);

        if($hangoutForm->isSubmitted() && $hangoutForm->isValid()) {

            $entityManager->persist($hangout);
            $entityManager->flush();


            $this->addFlash('success', 'Hangout added!!!');

            return $this->redirectToRoute('hangout_list');
        }
        return $this->render('hangout/create.html.twig',[
            'hangoutForm'=>$hangoutForm,
        ]);
    }
    #[Route('/list', name: 'list')]
    public function list(HangoutRepository $hr) {
        $hangouts = $hr->findAll();

        return $this->render('hangout/list.html.twig', [
           'hangouts' => $hangouts
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
