<?php

namespace App\Form;

use App\Entity\Hangout;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateHangoutType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('startTimestamp')
            ->add('duration')
            ->add('lastRegisterDate')
            ->add('maxSlots')
            ->add('informations')
            ->add('creator')
            ->add('schools')
            ->add('places')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Hangout::class,
        ]);
    }
}
