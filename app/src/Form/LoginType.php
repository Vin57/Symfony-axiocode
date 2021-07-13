<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('login', null, [
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'inputLogin',
                    'autofocus' => true,
                    'autocomplete' => 'username',
                    'placeholder' => 'login'
                ],
                'label' => false,
                'empty_data' => $options['last_username'],
                'required' => true
            ])
            ->add('password', PasswordType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'inputPassword',
                    'autocomplete' => 'current-password',
                    'placeholder' => 'password'
                ],
                'label' => false,
                'required' => true
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-lg btn-primary',
                    'label' => 'Sign in'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => LoginData::class,
            'method' => 'POST',
            'last_username' => null,
            'csrf_protection' => true,
            'csrf_token_id' => 'authenticate',
            'csrf_field_name' => '_csrf_token'
        ]);

        $resolver->addAllowedTypes('last_username', ['string', 'null']);
    }
}
