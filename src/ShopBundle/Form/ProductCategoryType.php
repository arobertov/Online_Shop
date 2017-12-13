<?php

namespace ShopBundle\Form;

use Doctrine\ORM\EntityRepository;
use ShopBundle\Entity\ProductCategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductCategoryType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name',TextType::class)
            ->add('parent',EntityType::class,array(
                'class'=>'ShopBundle\Entity\ProductCategory',
                'choice_label'=> 'name',
                'placeholder'=>'First Level Category',
                'query_builder'=> function (EntityRepository $er) {
		                return $er->createQueryBuilder('c')
		                          ->where('c.parent is NULL');
	                },
	            'required'=>false
            ));
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
