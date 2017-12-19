<?php

namespace ShopBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductUsersType extends AbstractType
{
	/**
	 * @var bool
	 */
	public static $userRestrict = false;

	public static $addQuantity = false;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	if(self::$userRestrict === true) {
		    $builder
			    ->add( 'quantity', HiddenType::class )
			    ->add( 'price', MoneyType::class );
	    }elseif (self::$addQuantity){
    		 $builder
			     ->add('quantity',IntegerType::class)
			     ->add('price',HiddenType::class)
			     ->add('hasSell',ChoiceType::class,array(
				     'label'=>'Please choice to do your product ',
				     'data'=>false,
				     'choices'  => array(
					     'I want to put up for sale' => true,
					     'Shipping order to my address' => false,
				     ),
				     'expanded'=>true,
				     'multiple'=>false
			     ))
		     ;
	    } else {
		    $builder
			    ->add( 'quantity', IntegerType::class )
			    ->add( 'price', MoneyType::class )
			    ->add('promotion', EntityType::class, array(
				    'class' => 'ShopBundle\Entity\Promotion',
				    'choice_label' => 'title',
				    'placeholder' => 'Without promotion !',
				    'required' => false
			    ))
			    ->add( 'product', ProductType::class, array(
				    'data_class' => 'ShopBundle\Entity\Product'
			    ) );
	    }


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ShopBundle\Entity\ProductUsers'
        ));
    }

    public function getBlockPrefix()
    {
        return 'shop_bundle_product_users_type';
    }
}
