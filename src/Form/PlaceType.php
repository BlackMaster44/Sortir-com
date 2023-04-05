<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Place;
use App\Form\Type\MapType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlaceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('name')
            ->add('street')
            ->add('city',EntityType::class,[
                'class'=>City::class,
                'choice_label' => 'name',
            ])
            ->add('map', MapType::class, [
                'mapped'=>false
            ])
            ->add('submit', SubmitType::class, [
                'label'=>'submit place',
                'attr'=>['class'=>'button place-form button-green']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Place::class,
            ]);
    }
}
