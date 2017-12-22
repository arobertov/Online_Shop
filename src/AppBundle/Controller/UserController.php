<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Role;
use AppBundle\Entity\UserEdit;
use AppBundle\Entity\User;
use AppBundle\Form\RoleType;
use AppBundle\Form\UserEditType;
use AppBundle\Form\UserType;
use AppBundle\Services\UserService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UserController
 * @package AppBundle\Controller
 * @Route("/user")
 */
class UserController extends Controller {
	/**
	 * @param Request $request
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 * @Route("/add_user",name="user_register")
	 */
	public function registerAction( Request $request ) {
		$user               = new User();
		UserType::$register = true;
		$form               = $this->createForm( UserType::class, $user );
		$form->handleRequest( $request );
		if ( $form->isSubmitted() && $form->isValid() ) {

			$userService = $this->get( UserService::class );
			$userService->registerUser( $user );

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
	 * @Security("has_role('ROLE_EDITOR')")
	 * @param Request $request
	 * @param User $user
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 * @Route("/users/{id}/edit",name="user_edit")
	 */
	public function usersEditAction( Request $request, User $user ) {
		$editForm = $this->createForm( UserType::class, $user, array( 'role' => $user->getRoleId() ) );
		$editForm->handleRequest( $request );

		if ( $editForm->isSubmitted() ) {
			$this->getDoctrine()->getManager()->flush();

			return $this->redirectToRoute( 'user_manager' );
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
		$user->setPlainPassword( '~~~~~~~~~' );
		$deleteForm = $this->createForm( UserType::class, $user, array( 'role' => $user->getRoleId() ) );
		$deleteForm->handleRequest( $request );

		if ( $deleteForm->isSubmitted() && $deleteForm->isValid() ) {
			$em = $this->getDoctrine()->getManager();
			$em->remove( $user );
			$em->flush();

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
	 * @param User $user
	 */
	public function activationEmailSetUser( User $user ) {
		$user->setIsActive( 1 );
		$em = $this->getDoctrine()->getManager();
		$em->flush();

		return $this->redirectToRoute( 'login' );
	}

	/**
	 * @return \Symfony\Component\HttpFoundation\Response
	 *
	 * @param Request $request
	 * @Route("/forgot_password",name="forgot_password")
	 */
	public function forgotPasswordAction( Request $request ) {
		$user = new UserEdit();
		$form = $this->createForm( UserEditType::class, $user );
		$form->handleRequest( $request );

		if ( $form->isSubmitted() && $form->isValid() ) {
			$userService = $this->get( UserService::class );
			$userService->forgotPassword( $user );

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
		UserEditType::$change = true;
		$userEdit             = new UserEdit();
		$form                 = $this->createForm( UserEditType::class, $userEdit );
		$form->handleRequest( $request );

		if ( $form->isSubmitted() && $form->isValid() ) {
			$userService = $this->get( UserService::class );
			$userService->changePassword( $user, $userEdit );

			return $this->redirectToRoute( 'my_profile', array( 'id' => $user->getId() ) );
		}

		return $this->render( '@basic/security/change_password.html.twig', [
			'form' => $form->createView()
		] );
	}
}
