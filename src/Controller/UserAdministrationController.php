<?php

namespace App\Controller;

use App\Form\UserModifyType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserAdministrationController extends AbstractController
{
    #[Route('/user/admin', name: 'user_administration')]
    public function index(): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UserModifyType::class, $user);
        return $this->render('user_administration/index.html.twig', ['form'=>$form]);
    }
}
