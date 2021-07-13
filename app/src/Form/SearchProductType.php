<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use App\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'required' => false
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'required' => false,
                'multiple' => false,
                'expanded' => false,
                'query_builder' => fn (CategoryRepository $r) => $r->getAllCategoryQB(),
                'choice_label' => fn(Category $c) => $c->getId() . ' - ' . $c->getName()
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchProductData::class,
            'method' => 'GET'
        ]);
    }
}
