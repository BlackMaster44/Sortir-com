<?php

namespace App\Form;

use App\Form\Model\HangoutFilterTypeModel;
use App\Form\Type\SiteType;
use DateTime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HangoutFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('isOrganizer', CheckboxType::class, [
                'label'=>'Sorties dont je suis l\'organisateur/trice',
                'required'=>false
            ])
            ->add('isSubscribed', CheckboxType::class, [
                'label'=>'Sorties auxquelles je suis inscrit/e',
                'required'=>false
            ])
            ->add('isNotSubscribed', CheckboxType::class, [
                'label'=>'Sorties auxquelles je ne suis pas inscrit/e',
                'required'=>false
            ])
            ->add('isExpired', CheckboxType::class, [
                'label'=>'Sorties passées',
                'required'=>false
            ])
            ->add('site', SiteType::class)
            ->add('searchQuery', SearchType::class, [
                'required'=>false
            ])
            ->add('from', DateType::class, [
                'widget' => 'single_text',
                'required' =>false,
                'placeholder'=> function () {
                    $date = new DateTime();
                }
            ])
            ->add('to', DateType::class, [
                'widget' => 'single_text',
                'required' => false
            ])
            ->add('filter', SubmitType::class, [
                'attr'=>['class'=>'button']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' =>HangoutFilterTypeModel::class,
            'attr' =>['class' => 'hangout-filter-form']
        ]);
    }
}
