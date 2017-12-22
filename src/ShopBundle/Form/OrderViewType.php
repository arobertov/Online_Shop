<?php

namespace ShopBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderViewType extends AbstractType {
	public function buildForm( FormBuilderInterface $builder, array $options ) {
		$builder ->add('choise',ChoiceType::class,array(
			 'choices'=>array(
			 	'Priduct name A - Z'=>'p.title-ASC',
				 'Priduct name Z - A'=>'p.title-DESC',
				 'Price Lowest first'=>'pu.price-ASC',
				 'Price Biggest first'=>'pu.price-DESC'
			 )))
		  ;
	}

	public function configureOptions( OptionsResolver $resolver ) {

	}

	public function getBlockPrefix() {
		return 'shop_bundle_order_view_tipe';
	}
}
