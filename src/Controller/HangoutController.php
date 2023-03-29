<?php

namespace App\Controller;

use App\Entity\Hangout;
use App\Entity\User;
use App\Form\CreateHangoutType;
use App\Repository\HangoutRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

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

    #[Route('/filter', name: 'filter')]
    public function filter(): Response {

        return $this->redirectToRoute('hangout_list', []);
    }

    #[Route('details/{id}', name: 'details')]
    public function details(int $id, HangoutRepository $hr): Response
{
    $hangout = $hr->find($id);

    return $this->render('hangout/details.html.twig', [
        'hangout' => $hangout
    ]);
}

    #[Route('/goingTo/{idHangout}', name: 'goingTo')]
    public function goingTo(int $idHangout,
                            #[CurrentUser] ?user $user,
                            HangoutRepository $hr,
                            EntityManagerInterface $emi): Response {

        $hangout = $hr->find($idHangout);
        $hangout->addParticipant($user);

        $emi->flush();

    return $this->render('hangout/details.html.twig', [
        'hangout'=> $hangout
    ]);
}

    #[Route('/notGoingAnymore/{idHangout}', name: 'notGoingAnymore')]
    public function notGoingAnymore (int $idHangout,
                                     #[CurrentUser] ?user $user,
                                     HangoutRepository $hr,
                                     EntityManagerInterface $emi):Response {

        $hangout = $hr->find($idHangout);
        $hangout->removeParticipant($user);

        $emi->flush();

    return $this->render('hangout/details.html.twig', [
        'hangout'=>$hangout
    ]);
}


}
