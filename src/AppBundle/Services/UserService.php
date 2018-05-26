<?php
/**
 * Created by PhpStorm.
 * User: AngelRobertov
 * Date: 23.11.2017 г.
 * Time: 15:58
 */

namespace AppBundle\Services;


use AppBundle\Entity\Role;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
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
	 * @param UserPasswordEncoderInterface $encoder
	 */
	public function __construct( SendEmailService $sendEmailService, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder ) {
		$this->sendEmailService = $sendEmailService;
		$this->em               = $em;
		$this->passwordEncoder  = $encoder;
	}

	/**
	 * @param User $user
	 *
	 * @return string
	 */
	public  function registerUser(User $user){
        $password = $this->passwordEncoder
            ->encodePassword($user, $user->getPlainPassword());
        $em = $this->em;
        $user->setPassword($password);
        //--  initialise firs user and set role super admin
        if(!($em->getRepository(User::class)->findAll())){
            $roleAdmin = new Role();
            $roleUser = new Role();
            $roleAdmin->setName('ROLE_SUPER_ADMIN');
            $roleUser->setName('ROLE_USER');
            $em->persist($roleAdmin);
            $em->persist($roleUser);
            $em->flush();
            $user->setRoles($em->getRepository(Role::class)->findOneBy(['name'=>'ROLE_SUPER_ADMIN']));
        }else {
            $user->setRoles($em->getRepository(Role::class)->findOneBy(['name' => 'ROLE_USER']));
        }
        $em->persist($user);
        $em->flush();

        //-- send user confirmation email
		try{
			$verifyEmail = $this->sendEmailService->verifyRegistrationEmail($user);
		}   catch (Exception $e){
			throw new Exception($e->getMessage());
		}
        if($verifyEmail){
            return "New user " . $user->getUsername() . " successful created !
			Please visit your email address : " . $user->getEmail() . " for confirm registration !" ;
        } else {
        	throw  new Exception('User unable registered !');
        }
    }

	/**
	 * @param array $formData
	 *
	 * @return string
	 */
	public function forgotPassword(array $formData) {
		if ( filter_var($formData['email'],FILTER_SANITIZE_EMAIL) ) {
			$email    = $formData['email'];
		} else {
		  throw new Exception('Invalid user data!');
        }

		$userObject = $this->em->getRepository( User::class )->findOneBy( array( 'email' => $email ) );
		if ( !isset($userObject) || $email !== $userObject->getEmail() ) {
			throw  new Exception( 'No user with this email: ' . $email . '!' );
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
		return 'Your new password is sent to your email: ' . $email . ' !';
	}

	/**
	 * @param User $user
	 * @param array $formData
	 *
	 * @return string
	 */
	public function changePassword(User $user,array $formData){
		if( filter_var($formData['oldPassword'],FILTER_SANITIZE_STRING )
		   && filter_var($formData['newPassword'],FILTER_SANITIZE_STRING )){
			$oldPassword = $formData['oldPassword']; 
			$newPassword = $formData['newPassword'];
		} else {
			throw new Exception('Invalid user data !');
		}
        if($this->passwordEncoder->isPasswordValid($user,$oldPassword)) {
            $password = $this->passwordEncoder
                ->encodePassword($user,$newPassword);
            $em = $this->em;
            $user->setPassword($password);
            $em->persist($user);
            $em->flush();
            return 'Password change successful !';
        } else {
            throw new Exception('Old password mishmash !');
        }
    }
}