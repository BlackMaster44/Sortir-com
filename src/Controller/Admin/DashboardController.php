<?php

namespace App\Controller\Admin;

use App\Entity\City;
use App\Entity\Hangout;
use App\Entity\Place;
use App\Entity\Site;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);

        return $this->redirect($adminUrlGenerator
                    ->setController(UserCrudController::class)
                    ->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Sortir Com');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::section('Users');
        yield MenuItem::subMenu('Action', 'fas fa-bars')->setSubItems([
            MenuItem::linkToCrud('Create User', 'fas fa-plus', User::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Show users', 'fas fa-eye', User::class)
        ]);

        yield MenuItem::section('Hangouts');
        yield MenuItem::subMenu('Action', 'fas fa-bars')->setSubItems([
            MenuItem::linkToCrud('Create Hangout', 'fas fa-plus', Hangout::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Show hangouts', 'fas fa-eye', Hangout::class)
        ]);

        yield MenuItem::section('Sites');
        yield MenuItem::subMenu('Action', 'fas fa-bars')->setSubItems([
            MenuItem::linkToCrud('Create site', 'fas fa-plus', Site::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Show sites', 'fas fa-eye', Site::class)
        ]);

        yield MenuItem::section('Cities');
        yield MenuItem::subMenu('Action', 'fas fa-bars')->setSubItems([
            MenuItem::linkToCrud('Create city', 'fas fa-plus', City::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Show cities', 'fas fa-eye', City::class)
        ]);

        yield MenuItem::section('Places');
        yield MenuItem::subMenu('Action', 'fas fa-bars')->setSubItems([
            MenuItem::linkToCrud('Create place', 'fas fa-plus', Place::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Show places', 'fas fa-eye', Place::class)
        ]);
    }
}
