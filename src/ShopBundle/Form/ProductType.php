<?php

namespace ShopBundle\Form;

use ShopBundle\Entity\ProductCategory;
use ShopBundle\Entity\ProductUsers;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class)
            ->add('description', TextType::class)
            ->add('features', TextareaType::class, array(
                'required' => false
            ))
            ->add('information', TextareaType::class, array(
                'required' => false
            ))
            ->add('rating', IntegerType::class)
            ->add('category', EntityType::class, array(
                'class' => 'ShopBundle\Entity\ProductCategory',
                'choice_label' => function (ProductCategory $category) {
                    return $category->getParent() ?
                        "-- " . $category->getName() : strtoupper($category->getName());
                },
            ))
            ->add('promotion', EntityType::class, array(
                'class' => 'ShopBundle\Entity\Promotion',
                'choice_label' => 'title',
                'placeholder' => 'Without promotion !',
                'required' => false
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }

    public function getBlockPrefix()
    {
        return 'shop_bundle_product_type';
    }
}
