<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use App\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Valid;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'label' => false,
                'attr' => [
                    'placeholder' => "Product.name",
                    'class' => 'form-control'
                ],
            ])
            ->add(
                'pictures',
                CollectionType::class,
                [
                    'entry_type' => PictureType::class,
                    'allow_add' => true/*$this->security->isGranted('ROLE_ADMIN')*/,
                    'allow_delete' => true/*$this->security->isGranted('ROLE_ADMIN')*/,
                    'by_reference' => false,
                    'constraints' => [new Valid()],
                ]
            )
            ->add(
                'category',
                EntityType::class,
                [
                    'class' => Category::class,
                    'query_builder' => fn (CategoryRepository $r) => $r->getAllCategoryQB(),
                    'choice_label' => fn(Category $c) => $c->getId() . ' - ' . $c->getName()
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
