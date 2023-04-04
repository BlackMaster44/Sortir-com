<?php

namespace App\Admin\Fields;

use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;

final class IntervalField implements FieldInterface
{
    use FieldTrait;
    public static function new(string $propertyName, ?string $label = null): self
    {
        return (new self())
        ->setProperty($propertyName)
        ->setLabel($label)
        ->setTemplatePath('date_interval_template.html.twig');
    }
}