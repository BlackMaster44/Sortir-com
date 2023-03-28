<?php

namespace App\Controller;

use App\Repository\SiteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/site', name: 'site_')]
class SiteController extends AbstractController
{
    #[Route('/list', name: 'list')]
    public function index(SiteRepository $sr): Response
    {
        $sites = $sr->findAll();

        return $this->render('site/list.html.twig', [
            'sites' => $sites,
        ]);
    }

    #[Route('/deleteSite/{id}', name: 'deleteSite')]
    public function deleteSite(int $id,
                               Request $request,
                               SiteRepository $sr,
                               EntityManagerInterface $emi): Response
    {
        $site = $sr->find($id);
            $emi->remove($site);
            $emi->flush();

        return $this->redirectToRoute('site_list');
    }
}
