<?php

namespace App\Controller;

use App\Entity\Hangout;

use App\Entity\Place;
use App\Entity\User;
use App\Form\CancelHangoutType;
use App\Form\CreateHangoutType;

use App\Form\HangoutFilterType;
use App\Form\Model\HangoutFilterTypeModel;
use App\Form\PlaceType;
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
    public function createHangout(HangoutHandler $handler, Request $request,#[CurrentUser] $user, EntityManagerInterface $em): Response
    {
        $place = new Place();
        $hangout = new Hangout();
        $hangout->setState(StateConstraints::wordingState[0]);
        if($user instanceof User){
            $hangout->setCreator($user);
            $hangout->setSite($user->getSite());
        }
        $hangoutForm = $this->createForm(CreateHangoutType::class, $hangout);
        $placeForm = $this->createForm(PlaceType::class, $place);
        $placeForm->handleRequest($request);
        $hangoutForm->handleRequest($request);
        if($placeForm->isSubmitted() && $placeForm->isValid()){
            $place->setLatitude($placeForm->get('map')->get('latitude')->getData());
            $place->setLongitude($placeForm->get('map')->get('longitude')->getData());
            $em->persist($place);
            $em->flush();
        }else if($hangoutForm->isSubmitted() && $hangoutForm->isValid()) {
            $action = $hangoutForm->get('publish')->isClicked() ? 'published' : 'saved';
            $handler->save($hangout, $action);
            $this->addFlash('success flash', 'Hangout '.$action.' !!!');
            return $this->redirectToRoute('hangout_list');
        }
        return $this->render('hangout/create.html.twig',[
            'hangoutForm'=>$hangoutForm,
            'placeForm' => $placeForm
        ]);
    }
    #[Route('/list', name: 'list')]
    public function list(Request $request, #[CurrentUser] $user, EntityManagerInterface $em) {
        $data = new HangoutFilterTypeModel($em);
        $form = $this->createForm(HangoutFilterType::class, $data);
        $form->handleRequest($request);
        $data->userId = $user->getId();
        $hangouts = $em->getRepository(Hangout::class)->filterResults($data);
        usort($hangouts, fn(Hangout $a, Hangout $b)=> $a->getStartTimestamp() < $b->getStartTimestamp() ? 1 : -1);
        return $this->render('hangout/list.html.twig', [
            'form'=>$form,
           'hangouts' => $hangouts
        ]);
    }

    #[Route('details/{id}', name: 'details')]
    public function details(int $id, HangoutRepository $hr): Response
{
    $hangout = $hr->find($id);
    if(!$hangout->isIsPublished()){
        $this->addFlash('error', 'unauthorized');
        $this->redirectToRoute('hangout_list');
    }
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
        if($hangout->getState() != StateConstraints::REG_OPEN) {
            $this->addFlash('error', 'Can\'t subscribe to an event that is not open');
        }else {
            $hangout->addParticipant($user);
            $emi->flush();
            $this->addFlash('success', 'subscribed to event '.$hangout->getName());
        }
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
        if($hangout->getState() != StateConstraints::REG_OPEN){
            $this->addFlash('error', 'Can\'t unsubscribe from an event that is not open');
        }else{
            $hangout->removeParticipant($user);
            $emi->flush();
            $this->addFlash('success', 'unsubscribed from event '.$hangout->getName());
        }
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
