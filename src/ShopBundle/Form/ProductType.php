<?php

namespace ShopBundle\Form;

use Doctrine\ORM\EntityRepository;
use ShopBundle\Entity\ProductCategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\File\File;
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
	        ->add('image',FileType::class,array(
	        	'required'=>false
	        ))
            ->add('rating', IntegerType::class,array(
            	'required' => false
            ))
	        ->add('category',EntityType::class,array(
	        	'class'=>'ShopBundle\Entity\ProductCategory',
		        'choice_label'=> function (ProductCategory $category) {
			              return $category->getParent() ?
			                   "-- " . $category->getName() : strtoupper($category->getName());
			           },
		        'query_builder' => function(EntityRepository $er) {
			        return $er->createQueryBuilder('c')
			                  ->orderBy('c.root', 'ASC')
			                  ->addOrderBy('c.lft', 'ASC');
		        },
	        ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
	      $resolver->setDefault('data_class','ShopBundle\Entity\Product');
    }

    public function getBlockPrefix()
    {
        return 'shop_bundle_product_type';
    }
}
