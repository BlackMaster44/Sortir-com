<?php

namespace App\Controller\Admin;

use App\Admin\Fields\IntervalField;
use App\Entity\Hangout;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

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
            AssociationField::new('creator'),
            BooleanField::new('isPublished'),
            TextField::new('name'),
            DateTimeField::new('startTimeStamp'),
            DateTimeField::new('lastregisterDate'),
            IntervalField::new('duration'),
            NumberField::new('maxSlots'),
            AssociationField::new('participants'),
            AssociationField::new('site'),
            AssociationField::new('place'),
            TextField::new('informations'),
            TextField::new('state'),
            TextField::new('cancelReason')
        ];
    }

}
