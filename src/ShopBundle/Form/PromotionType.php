<?php

namespace ShopBundle\Form;

use ShopBundle\Entity\ProductCategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PromotionType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title',TextType::class)
                ->add('description',TextType::class,array(
                	'required'=>false
                ))
                ->add('discount',PercentType::class)
                ->add('dueDate',DateType::class)
                ->add('endDate',DateType::class)
	            ->add('isActive',ChoiceType::class,array(
	            	'choices'=>array(
	            		'Activate Promotion'=>true,
			            'Deactivate Promotion'=>false
		            )
	            ))
	            ->add('productCategory',EntityType::class,array(
		            'class' => 'ShopBundle\Entity\ProductCategory',
		            'placeholder'=>'All Categories !' ,
		            'choice_label' => function (ProductCategory $category) {
			            return $category->getParent() ?
				            "-- " . $category->getName() : strtoupper($category->getName());
		            },
		            'required'=>false
	            ))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ShopBundle\Entity\Promotion'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'shopbundle_promotion';
    }


}
