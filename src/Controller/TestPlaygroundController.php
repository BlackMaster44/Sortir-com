<?php

namespace App\Controller;

use App\Form\HangoutFilterType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
public function filterForm():Response
    {
        $form = $this->createForm(HangoutFilterType::class);
        return $this->render('test_playground/filter-form.html.twig', ['form'=>$form]);
    }
}
