<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePasswordType extends AbstractType {
	public function buildForm( FormBuilderInterface $builder, array $options ) {
		$builder
			->add('oldPassword',PasswordType::class)
			->add('newPassword',RepeatedType::class,array(
				'type' => PasswordType::class,
				'first_options' => array('label' => 'Password'),
				'second_options' => array('label' => 'Repeat Password'),
			))
		;
	}

	public function configureOptions( OptionsResolver $resolver ) {
		$resolver->setDefaults(array(
			'validation_groups' => false,
		));
	}

	public function getBlockPrefix() {
		return 'app_bundle_change_password_type';
	}
}
