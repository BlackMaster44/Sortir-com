<?php
namespace App\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MapType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('latitude', HiddenType::class, [
                'attr'=>['class'=>'latitude']
            ])
            ->add('longitude', HiddenType::class, [
                'attr'=>['class'=>'longitude']
            ]);
    }
    public function getBlockPrefix(): string
    {
        return 'map';
    }

    public function configureOptions(OptionsResolver $resolver)
    {}
}