<?php

namespace App\Controller;

use App\Entity\Hangout;

use App\Entity\User;
use App\Form\CancelHangoutType;
use App\Form\CreateHangoutType;

use App\Form\HangoutFilterType;
use App\Form\Model\HangoutFilterTypeModel;
use App\Repository\HangoutRepository;
use App\Services\HangoutHandler;
use App\TypeConstraints\StateConstraints;
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
    public function createHangout(HangoutHandler $handler, Request $request, EntityManagerInterface $entityManager,#[CurrentUser] $user): Response
    {
        $hangout = new Hangout();
        $hangout->setState(StateConstraints::wordingState[0]);
        if($user instanceof User){
            $hangout->setCreator($user);
            $hangout->setSite($user->getSite());
        }
        $hangoutForm = $this->createForm(CreateHangoutType::class, $hangout);
        $hangoutForm->handleRequest($request);
        if($hangoutForm->isSubmitted() && $hangoutForm->isValid()) {
            $action = $hangoutForm->get('publish')->isClicked() ? 'published' : 'saved';
            $handler->save($hangout, $action);
            $this->addFlash('success flash', 'Hangout '.$action.' !!!');
            return $this->redirectToRoute('hangout_list');
        }
        return $this->render('hangout/create.html.twig',[
            'hangoutForm'=>$hangoutForm,
        ]);
    }
    #[Route('/list', name: 'list')]
    public function list(Request $request, #[CurrentUser] $user, EntityManagerInterface $em) {
        $data = new HangoutFilterTypeModel($em);
        $form = $this->createForm(HangoutFilterType::class, $data);
        $form->handleRequest($request);
        $data->userId = $user->getId();
        $hangouts = $em->getRepository(Hangout::class)->filterResults($data);
        return $this->render('hangout/list.html.twig', [
            'form'=>$form,
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
                            #[CurrentUser] $user,
                            HangoutRepository $hr,
                            EntityManagerInterface $emi) {

        $hangout = $hr->find($idHangout);
        $hangout->addParticipant($user);

        $emi->flush();

    return $this->render('hangout/details.html.twig', [
        'hangout'=>$hangout
    ]);
}

    #[Route('/notGoingAnymore/{idHangout}', name: 'notGoingAnymore')]
    public function notGoingAnymore (int $idHangout,
                                     #[CurrentUser] $user,
                                     HangoutRepository $hr,
                                     EntityManagerInterface $emi) {

        $hangout = $hr->find($idHangout);
        $hangout->removeParticipant($user);

        $emi->flush();

    return $this->render('hangout/details.html.twig', [
        'hangout'=>$hangout
    ]);
}


    #[Route('cancel/{idHangout}', name:'cancel')]
    public function cancel(int $idHangout,
                           Request $request,
                           EntityManagerInterface $emi,
                           HangoutRepository $hr): Response {

        $hangout = $hr->find($idHangout);
        $cancelHangoutForm = $this->createForm(CancelHangoutType::class, $hangout);
        $cancelHangoutForm->handleRequest($request);

        if($cancelHangoutForm->isSubmitted() && $cancelHangoutForm->isValid()) {
            $hangout->setState('canceled');
            $emi->flush();

            $this->addFlash('success flash', 'Hangout successfully canceled');

            return $this->render('hangout/details.html.twig', [
                'hangout'=>$hangout
            ]);
        }

        $this->addFlash('notice flash', 'Hangout not canceled');

        return $this->render('hangout/cancel.html.twig', [
            'cancelHangoutForm'=>$cancelHangoutForm
        ]);
    }
}
