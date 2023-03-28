<?php

namespace App\Form;

use App\Form\Type\SiteType;
use App\Form\Type\FilterCheckboxSelectorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;

class HangoutFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('checkboxSelection', FilterCheckboxSelectorType::class)
            ->add('city', SiteType::class)
            ->add('from', DateType::class, [
                'constraints'=>[
                    new Date(),
                ],
            ])
            ->add('to', DateType::class, [
                'constraints'=>[
                    new Date(),
                    new GreaterThan([
                        'propertyPath'=>'parent.all[from].data'
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
