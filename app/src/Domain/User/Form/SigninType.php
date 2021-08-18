<?php

namespace App\Domain\User\Form;

use App\Domain\User\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SigninType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('login', null, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('email', RepeatedType::class, [
                'type' => EmailType::class,
                'label' => 'email',
                'first_options' => [
                    'label' => 'email',
                    'attr' => ['class' => 'form-control']
                ],
                'second_options' => [
                    'label' => 'repeat email',
                    'attr' => ['class' => 'form-control']
                ],

            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'label' => 'password',
                'first_options' => [
                    'label' => 'password',
                    'attr' => ['class' => 'form-control']
                ],
                'second_options' => [
                    'label' => 'repeat password',
                    'attr' => ['class' => 'form-control']
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'signin',
                'attr' => [
                    'class' => 'btn btn-success form-control'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
