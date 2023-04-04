<?php

namespace App\Controller\Admin;

use App\Entity\Place;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PlaceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Place::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name'),
            TextField::new('street'),
            TextField::new('latitude'),
            TextField::new('longitude'),
            TextField::new('city'),
            ArrayField::new('hangouts')
        ];
    }

    public function deleteEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if(!$entityInstance instanceof Place) return;
        foreach ($entityInstance->getHangouts() as $hangout) {
            $entityManager->remove($hangout);
        }

        parent::deleteEntity($entityManager, $entityInstance);
    }

}
