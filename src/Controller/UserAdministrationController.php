<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserModifyType;
use App\Form\UserPasswordChangeType;
use App\Services\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/user/admin', name: 'user')]
class UserAdministrationController extends AbstractController
{
    #[Route('/', name: '_view')]
    public function view():Response
    {
        return $this->render('user_administration/view.html.twig');
    }
    #[Route('/modify', name: '_modify')]
    public function modify(Request $request, EntityManagerInterface $em, FileUploader $fileUploader, #[CurrentUser] $user): Response
    {
        $form = $this->createForm(UserModifyType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $profilePic = $form->get('imageUrl')->getData();
            if($profilePic){
                $filePath = $fileUploader->upload($profilePic);
                if($user instanceof User){
                    $user->setImageUrl($filePath);
                }
            }
            $user = $form->getData();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('user_view');
        }
        return $this->render('user_administration/index.html.twig', ['form'=>$form]);
    }
    #[Route('/modify/password', name: '_password')]
    public function changePassword(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $hasher): Response
    {
        $user = $em->getRepository(User::class)->findOneBy(['email'=>$this->getUser()->getUserIdentifier()]);
        $form = $this->createForm(UserPasswordChangeType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $isEqual = $form->get('password')->getData() === $form->get('passwordValidation')->getData();
            if ($isEqual && $form->isValid()){
                $user->setPassword($hasher->hashPassword($user, $user->getPassword()));
                $em->persist($user);
                $em->flush();
                return $this->redirectToRoute('user_view');
            }
        }
        return $this->render('user_administration/password.html.twig', ['form'=>$form]);
    }
}
