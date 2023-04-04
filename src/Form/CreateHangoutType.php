<?php

namespace App\Form;

use App\Entity\Hangout;
use App\Entity\Place;
use DateTime;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateHangoutType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $date = new DateTime();
        $formattedDateTime = $date->format('Y-m-d H:i');
        $formattedDate = $date->format('Y-m-d');
        $builder
            ->add('name')
            ->add('startTimestamp',DateTimeType::class, [
                'widget' => 'single_text',
                'attr'=>['value'=>$formattedDateTime]
            ])
            ->add('lastRegisterDate',DateType::class, [
                'widget' => 'single_text',
                'attr'=>['value'=>$formattedDate]

            ])
            ->add('maxSlots')
            ->add('duration', DateIntervalType::class, [
                'widget' => 'integer',
                'with_years' => false,
                'with_months' => false,
                'with_days' => false,
                'with_hours' => true,
                'with_minutes' => true,
            ])
            ->add('informations', TextareaType::class)
            ->add('place', EntityType::class, [
                'class' => Place::class,
                'choice_label' => 'name',
                'attr'=>['class'=>'label-choice']
            ])
            ->add('addPlace', ButtonType::class, [
                'label' => 'Add Place...',
                'attr'=>['class'=>'button add-place-button']
            ])

            ->add('publish', SubmitType::class, [
                'attr'=>['class' =>'button publish-button button-green']
            ])
            ->add('save', SubmitType::class, [
                'attr'=>['class'=>'button save-button']
            ])
            ->add('cancel', ButtonType::class, [
                'label'=>'Cancel Hangout creation',
                'attr'=>['class' => 'button cancel-hangout button-red']
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
