<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('price')
            ->add('stock')
            ->add('image', FileType::class, [
                'label'=>'image de produit',
                'mapped' => false,
                'required' => false,
                'constraints' => [new File([
                    "maxSize"=>"1024k",
                    "mimeTypes"=>['image/jpeg','image/png','image/jpg'],
                    "mimeTypesMessage"=>"Veuillez choisir une image au format valide(png, jpg,jpeg)",
                    "maxSizeMessage"=>"Votre image ne doit pas depasser 1024k",

                ])]
            ])
            ->add('Category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'placeholder' => 'Choisir une catégorie',
                'required' => true,
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
