<?php

namespace ShopBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Yavin\Symfony\Form\Type\TreeType;

class ProductCategoryType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
	        ->add('name',TextType::class)
	        ->add('parent',TreeType::class,array(
		        'class'=>'ShopBundle\Entity\ProductCategory',
		        'placeholder'=>'First Level Category',
		        'required'=>false,
		        'levelPrefix' => '-',
		        'orderFields' => ['lft' => 'asc'],
		        'prefixAttributeName' => 'data-level-prefix',
		        'treeLevelField' => 'lvl',
	        ))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ShopBundle\Entity\ProductCategory'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'shopbundle_productcategory';
    }


}
