<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Hangout;
use App\Entity\Place;
use App\Entity\Site;
use App\Repository\CityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
            ->add('lastRegisterDate')
            ->add('maxSlots')
            ->add('duration')
            ->add('informations')
            ->add('place',EntityType::class,[
                'class'=>Place::class,
                'choice_label'=>'name',


            ])





        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Hangout::class,
        ]);
    }
}
