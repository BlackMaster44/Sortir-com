<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user', name: 'user_')]
class UserController extends AbstractController
{
    #[Route('/profile/{id}', name: 'profile')]
    public function index(int $id,
                          UserRepository $ur,
                          EntityManagerInterface $emi): Response
    {
        $user = $ur->find($id);

        return $this->render('user/profile.html.twig', [
            'user'=>$user
        ]);
    }
}
