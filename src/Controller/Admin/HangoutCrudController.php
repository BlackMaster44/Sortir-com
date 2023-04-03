<?php

namespace App\Controller\Admin;

use App\Entity\Hangout;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TimeField;

class HangoutCrudController extends AbstractCrudController
{

    public static function getEntityFqcn(): string
    {
        return Hangout::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('creator'),
            TextField::new('name'),
            DateTimeField::new('startTimeStamp'),
            DateTimeField::new('lastregisterDate'),
            NumberField::new('maxSlots'),
            ArrayField::new('participants'),
            AssociationField::new('site'),
            AssociationField::new('place'),
            TextField::new('informations'),
            TextField::new('state'),
            TextField::new('cancelReason')
        ];
    }

}
