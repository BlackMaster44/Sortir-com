<?php

namespace App\Form\Type;

use App\TypeConstraints\FilterCheckboxSelectorConstraints;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterCheckboxSelectorType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'choices'=>array_flip(FilterCheckboxSelectorConstraints::CHOICES),
            'expanded'=>true,
            'multiple'=>true,
            'mapped'=>false
        ]);
    }

    public function getParent()
    {
        return ChoiceType::class;
    }

}