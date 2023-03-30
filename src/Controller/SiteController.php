<?php

namespace App\Controller;

use App\Entity\Site;
use App\Form\CreateSiteType;
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
    public function list(SiteRepository $sr): Response
    {
        $sites = $sr->findAll();

        return $this->render('site/list.html.twig', [
            'sites' => $sites,
        ]);
    }

    #[Route('/create', name: 'create')]
    public function createSite(Request $request,
                               EntityManagerInterface $emi): Response {

        $site = new Site();
        $siteForm = $this->createForm(CreateSiteType::class, $site);
        $siteForm->handleRequest($request);

        if($siteForm->isSubmitted() && $siteForm->isValid()) {
            $emi->persist($site);
            $emi->flush();

            $this->addFlash('success', 'Site successfully added');

            return $this->redirectToRoute('site_list');
        }

        return $this->render('site/create.html.twig', [
            'siteForm'=> $siteForm->createView()
        ]);
    }

    #[Route('/update/{idSite}', name: 'update')]
    public function updateSite(Request $request,
                               int $idSite,
                               SiteRepository $sr,
                               EntityManagerInterface $emi): Response {

        $site = $sr->find($idSite);
        $siteForm = $this->createForm(CreateSiteType::class, $site);
        $siteForm->handleRequest($request);

        if ($siteForm->isSubmitted() && $siteForm->isValid()) {
            $emi->flush();
            $this->addFlash('success', 'Site successfully updated');

            return $this->redirectToRoute('site_list');
        }

        return $this->render('site/create.html.twig', [
            'siteForm'=>$siteForm
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
