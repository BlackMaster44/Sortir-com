<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserCreateType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
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

    #[Route('/create', name: 'create')]
    public function create(Request $request,
                           UserPasswordHasherInterface $passwordHasher,
                           EntityManagerInterface $emi) {

        $plaintextPassword = 'password';

        $user = new User();
        $hashedPassword = $passwordHasher->hashPassword($user, $plaintextPassword);

        $userForm = $this->createForm(UserCreateType::class, $user);
        $userForm->handleRequest($request);

        $user->setPassword($hashedPassword);
        $user->setActive(1);
        $user->setAdministrator(0);

        if($userForm->isSubmitted() && $userForm->isValid()) {
            $emi->persist($user);
            $emi->flush();

            $this->addFlash('success', 'User successfully created');

            return $this->redirectToRoute('hangout_list');
        }

        return $this->render('user_administration/create.html.twig', [
            'userCreationForm' => $userForm
        ]);
    }
}
