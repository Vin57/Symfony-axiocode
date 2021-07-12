<?php

namespace App\Form;

use App\Entity\Opinion;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OpinionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('comment', TextareaType::class)
            ->add('rating', RangeType::class, [
                'attr' => [
                    'min' => Opinion::RATING_MIN_VALUE,
                    'max' => Opinion::RATING_MAX_VALUE,
                ],
                'empty_data' => 1
            ])
            ->add('product', EntityType::class, [
                'class' => Product::class,
                'choice_label' => fn (Product $p) => $p->getName(),
                'attr' => [
                    'hidden' => true
                ]
            ])
            ->add('user', CurrentUserType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Opinion::class,
        ]);
    }
}
