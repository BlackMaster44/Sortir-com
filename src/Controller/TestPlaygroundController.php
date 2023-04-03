<?php

namespace App\Controller;

use App\Entity\Hangout;
use App\Entity\User;
use App\Form\HangoutFilterType;
use App\Form\Model\HangoutFilterTypeModel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/public/test', name: 'test')]
class TestPlaygroundController extends AbstractController
{
    #[Route('/', name:'_home')]
    public function index(): Response
    {
        return $this->render('test_playground/index.html.twig');
    }
    #[Route('/filter-form', name:'_filter-form')]
    public function filterForm(Request $req, EntityManagerInterface $em):Response
    {
        $data = new HangoutFilterTypeModel($em);
        $user = $this->getUser();
        $form = $this->createForm(HangoutFilterType::class, $data);
        $form->handleRequest($req);
        $data->userId = $user instanceof User ? $user->getId() : 0;
        $sites = $em->getRepository(Hangout::class)->filterResults($data);
        var_dump("size of sites ".sizeof($sites));
        return $this->render('test_playground/filter-form.html.twig', ['form'=>$form, 'sites'=>$sites]);
    }

    #[Route('/buttons', name: '_buttons')]
    public function buttons(Request $req): Response
    {
        return $this->render('test_playground/buttons.html.twig');
    }

    #[Route('/alerts', name: '_alerts')]
    public function alerts():Response
    {
        return $this->render('test_playground/alert.html.twig');
    }
}
