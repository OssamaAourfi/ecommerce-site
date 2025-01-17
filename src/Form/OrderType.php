<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Order;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName',null,[
                'label' => 'First Name',
                'attr' => [
                    'class' => 'form form-control',
                ]
            ])
            ->add('lastName',null,[
        'label' => 'Last Name',
        'attr' => [
            'class' => 'form form-control',
        ]
    ])
            ->add('phone',null,[
                'label' => 'Phone Number',
                'attr' => [
                    'class' => 'form form-control',
                ]
            ])
            ->add('adresse',null,[
                'label' => 'Adresse',
                'attr' => [
                    'class' => 'form form-control',
                ]
            ])
//            ->add('createdAt', null, [
//                'widget' => 'single_text',
//            ])
            ->add('city', EntityType::class, [
                'label' => 'Ville',
                'class' => City::class,
                'choice_label' => 'name',
                'attr' => [
                    'class' => 'form form-control',
                ]
            ])
            ->add('payOnDelivery',null,['label' => 'payer a la livraison'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
