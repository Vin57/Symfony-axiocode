<?php

namespace App\Domain\Product\Form;

use App\Domain\Product\Entity\Picture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class PictureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('name', null, [
                'attr' => ['placeholder' => 'image.name'],
                'required' => false
            ])
            ->add('is_main', null, [
                'label' => 'main.product.picture'
            ])
            ->add('file', VichImageType::class, [
                'required' => false,
                'download_label' => false,
                'label' => false,
                'delete_label' => false,
                'allow_delete' => false,
                'asset_helper' => true,
                'download_link' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Picture::class,
            'allow_file_upload' => true
        ]);
    }
}
