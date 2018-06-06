<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Role;
use AppBundle\Entity\UserEdit;
use AppBundle\Entity\User;
use AppBundle\Form\ChangePasswordType;
use AppBundle\Form\ForgotPasswordType;
use AppBundle\Form\RoleType;
use AppBundle\Form\UserEditType;
use AppBundle\Form\UserType;
use AppBundle\Services\UserService;
use AppBundle\Services\UserServiceInterface;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class UserController
 * @package AppBundle\Controller
 * @Route("/user")
 */
class UserController extends Controller {

	/**
	 * @var UserServiceInterface
	 */
	protected $userService;

	/**
	 * UserController constructor.
	 *
	 * @param UserServiceInterface $userService
	 */
	public function __construct( UserServiceInterface $userService) {
		$this->userService = $userService;
	}


	/**
	 * @param Request $request
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 * @Route("/add_user",name="user_register")
	 */
	public function registerAction( Request $request ) {
		$user               = new User();
		UserType::$fieldsSwitcher = 'registration';
		$form               = $this->createForm( UserType::class, $user, array(
			'validation_groups'=>array('Default','registration')
		) );
		$form->handleRequest( $request );
		if ( $form->isSubmitted() && $form->isValid() ) {
			try {
				$message = $this->userService->registerUser( $user );
			}   catch (Exception $e) {
				$this->addFlash('error',$e->getMessage());
				return $this->render( "@basic/security/register.html.twig", [
					'form' => $form->createView()
				] );
			}
			$this->addFlash( 'success', $message );

			return $this->redirectToRoute( "login" );
		}

		return $this->render( "@basic/security/register.html.twig", [
			'form' => $form->createView()
		] );
	}


	/**
	 * @param Request $request
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
	 *
	 * @Route("/add_role",name="create_role")
	 * @Security("has_role('ROLE_SUPER_ADMIN')")
	 */
	public function addUserRoleAction( Request $request ) {
		$role     = new Role();
		$em       = $this->getDoctrine()->getManager();
		$allRoles = $em->getRepository( 'AppBundle:Role' )->findAll();
		$form     = $this->createForm( RoleType::class, $role );
		$form->handleRequest( $request );
		if ( $form->isSubmitted() && $form->isValid() ) {
			$em->persist( $role );
			$em->flush();
		}

		return $this->render( '@App/security/add_role.html.twig', array(
			'roles' => $allRoles,
			'form'  => $form->createView()
		) );
	}

	/**
	 * @param User $user
	 *
	 * @return Response
	 * @Route("/my_profile/{id}",name="my_profile")
	 */
	public function viewMyProfileAction( User $user ) {

		return $this->render( '@basic/security/view_user_profile.html.twig', [
			'user' => $user
		] );
	}

	/**
	 * @Route("/users",name="user_manager")
	 * @Security("has_role('ROLE_EDITOR')")
	 */
	public function userManagerAction() {
		$em    = $this->getDoctrine()->getManager();
		$users = $em->getRepository( User::class )->findAll();

		return $this->render( '@basic/security/all_user.html.twig', [
			'users' => $users,
		] );

	}

	/**
	 * @Security("has_role('ROLE_USER')")
	 * @param Request $request
	 * @param User $user
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 * @Route("/users/{id}/edit",name="user_edit")
	 */
	public function userEditAction( Request $request, User $user ) {
		UserType::$fieldsSwitcher = 'edit';
		$em = $this->getDoctrine()->getRepository(User::class);
		$adminUser = $em->findRoleUser(['name'=>'ROLE_SUPER_ADMIN']);
		$editForm = $this->createForm( UserType::class, $user, array(
			'role' => $adminUser->getId(),
			'validation_groups'=>array('Default')
		) );
		$editForm->handleRequest( $request );
		if ( $editForm->isSubmitted() && $editForm->isValid() ) {
			$this->userService->editUser($user);
			
			$this->addFlash( 'success', 'Your profile edit successful !' );
			return $this->redirectToRoute( 'my_profile', [ 'id' => $user->getId() ] );
		}

		return $this->render( '@basic/security/edit_user.html.twig', [
			'user'      => $user,
			'edit_form' => $editForm->createView()
		] );
	}

	/**
	 * @Security("has_role('ROLE_ADMIN')")
	 * @param Request $request
	 * @param User $user
	 * @Route("/{id}/delete_user",name="user_delete")
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function userDeleteAction( Request $request, User $user ) {
		UserType::$fieldsSwitcher = 'edit';
		$deleteForm = $this->createForm( UserType::class, $user, array(
			'role' => $user->getRoleId(),
			'validation_groups'=>array('Default')
		) );
		$deleteForm->handleRequest( $request );

		if ( $deleteForm->isSubmitted() && $deleteForm->isValid() ) {
			$this->userService->removeUser($user);
			$this->addFlash('success','User in username '. $user->getUsername() . ' delete successful !');
			return $this->redirectToRoute( 'user_manager' );
		}

		return $this->render( '@basic/security/delete_user.html.twig', [
			'user'        => $user,
			'delete_form' => $deleteForm->createView()
		] );
	}

	/**
	 * Set user for link from activation email
	 * @return \Symfony\Component\HttpFoundation\Response
	 * @Route("/activate_user/{id}",name="activate_user")
	 * @Method("GET")
	 *
	 * @param $id
	 */
	public function activationEmailSetUser( $id ) {
		$this->userService->checkRegisteredUserDate();
		$em = $this->getDoctrine()->getManager();
		$user = $em->getRepository(User::class)->find($id);
		if($user->isNotExpired() === true){
			$user->setIsActive( 1 );
			$em = $this->getDoctrine()->getManager();
			$em->flush();
			$this->addFlash('success',"You Account  successful activated !Please Login !");
			return $this->redirectToRoute( 'login' );
		}
		$this->addFlash('error',
			"Your Account  is not activated. More than 48 hours have elapsed since your registration!
			Please register again !"
		);
		return $this->redirectToRoute('user_register');
	}

	/**
	 * @return \Symfony\Component\HttpFoundation\Response
	 *
	 * @param Request $request
	 * @Route("/forgot_password",name="forgot_password")
	 */
	public function forgotPasswordAction( Request $request ) {
		$form = $this->createForm( ForgotPasswordType::class );
		$form->handleRequest( $request );

		if ( $form->isSubmitted() && $form->isValid() ) {
			try {
				$message = $this->userService->forgotPassword( $form->getData() );
			} catch ( Exception $e ) {
				$this->addFlash( 'error', $e->getMessage() );

				return $this->render( "@basic/security/forgot_password.html.twig", array(
					'form' => $form->createView()
				) );
			}
			$this->addFlash( 'success', $message );
			return $this->redirectToRoute( 'login' );
		}

		return $this->render( "@basic/security/forgot_password.html.twig", array(
			'form' => $form->createView()
		) );
	}


	/**
	 * @param User $user
	 * @param Request $request
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
	 * @Route("/{id}/change_password",name="change_password")
	 */
	public function changePasswordAction( User $user, Request $request ) {
		UserType::$fieldsSwitcher = 'change_password';
		$form = $this->createForm( UserType::class,$user,array(
			'validation_groups'=>array('registration','change_password')
		) );
		$form->handleRequest( $request );

		if ( $form->isSubmitted() && $form->isValid() ) {
			try {
				$message = $this->userService->changePassword( $user );
			} catch ( Exception $e ) {
				$this->addFlash( 'error', $e->getMessage() );

				return $this->render( '@basic/security/change_password.html.twig', [
					'form' => $form->createView()
				] );
			}
			$this->addFlash( 'success', $message );

			return $this->redirectToRoute( 'my_profile', array( 'id' => $user->getId() ) );
		}

		return $this->render( '@basic/security/change_password.html.twig', [
			'form' => $form->createView()
		] );
	}
}
