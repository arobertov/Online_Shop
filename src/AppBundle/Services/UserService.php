<?php
/**
 * Created by PhpStorm.
 * User: AngelRobertov
 * Date: 23.11.2017 г.
 * Time: 15:58
 */

namespace AppBundle\Services;


use AppBundle\Entity\ForgotPassword;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserService {
	/**
	 * @var SendEmailService
	 */
	private $sendEmailService;

	/**
	 * @var EntityManagerInterface
	 */
	private $em;

	/**
	 * @var UserPasswordEncoderInterface
	 */
	private $passwordEncoder;

	/**
	 * UserService constructor.
	 *
	 * @param SendEmailService $sendEmailService
	 * @param EntityManagerInterface $em
	 */
	public function __construct( SendEmailService $sendEmailService, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder ) {
		$this->sendEmailService = $sendEmailService;
		$this->em               = $em;
		$this->passwordEncoder  = $encoder;
	}


	public function forgotPassword( ForgotPassword $validate ) {
		if ( isset( $validate ) ) {
			$username = $validate->getUsername();
			$email    = $validate->getEmail();
		}
		$userObject = $this->em->getRepository( User::class )->findOneBy( array( 'username' => $username ) );

		if ( $userObject === null ) {
			die( 'username ( ' . $username . ' ) not found' );
		}

		if ( $email !== $userObject->getEmail() ) {
			die( 'email ' . $email . ' not found' );
		}
		 //-- generate new random password
		$randomPassword = substr( $userObject->getPassword(), 8, 8 );
		//-- hashing random password
		$password       = $this->passwordEncoder->encodePassword( $userObject, $randomPassword );

		$userObject->setPassword( $password );
		//-- send email with new password
		$this->sendEmailService->forgotPasswordEmail( $randomPassword, $userObject );

		$this->em->persist( $userObject );
		$this->em->flush();


	}
}