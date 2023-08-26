<?php

namespace App\Form;

use App\Entity\Products;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('prixV', IntegerType::class)
            ->add('stockI', IntegerType::class, [
                'disabled' => $options['isEdit'],
            ])
            ->add('stockLivrer', IntegerType::class, [
                'disabled' => $options['isEdit'],
            ])
            // ->add('stockTotal', IntegerType::class, [
            //     'label' => 'Stock total',
            //     'disabled' => $options['isEdit'],
            // ])
            // ->add('stockFinal', IntegerType::class)
            ->add('stockFinal', IntegerType::class, [
                'label' => 'Stock final',
                'disabled' => $options['isEdit'],
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Products::class,
            'isEdit' => false,
        ]);
    }
}
