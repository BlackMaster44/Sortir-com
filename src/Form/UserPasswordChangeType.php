<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserPasswordChangeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('password', PasswordType::class,
                [
                    'constraints' =>[
                        new NotBlank()
                ]
            ])
            ->add('passwordValidation', PasswordType::class,
                [
                    'mapped' => false,
                    'label'=>'Re enter password',
                    'constraints' => [
                        new NotBlank(),
                    ]
                ]
            )
            ->add('Modifier', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'attr'=>['class' => 'user-form']
        ]);
    }
}
