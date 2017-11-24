<?php

namespace BlogBundle\Controller;
use BlogBundle\Entity\ForgotPassword;
use BlogBundle\Entity\User;
use BlogBundle\Form\ForgotPasswordType;
use BlogBundle\Form\UserType;
use BlogBundle\Services\SendEmailService;
use BlogBundle\Services\UserService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/login",name="login")
     */
    public function loginAction()
    {
        // get the login error if there is one
        $authUtils = $this->get('security.authentication_utils');
        $error = $authUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authUtils->getLastUsername();


        return $this->render('@basic/security/login.html.twig', array(
            'last_username' => $lastUsername,
            'error' => $error,
        ));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("add_user",name="user_register")
     */
    public function registerAction(Request $request)
    {
        $user = new User();
        UserType::$register = true;
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            // send user confirmation email

            $verifyEmail = $this->get(SendEmailService::class);
            $verifyEmail->verifyRegistrationEmail($user);
            return $this->redirectToRoute("login");
        }
        return $this->render("@basic/security/register.html.twig", [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/users",name="user_manager")
     */
    public function userManagerAction()
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository(User::class)->findAll();

        return $this->render('@basic/security/all_user.html.twig', [
            'users' => $users,
        ]);

    }

    /**
     * @param Request $request
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/users/{id}/edit",name="user_edit")
     */
    public function userEditAction(Request $request, User $user)
    {

        $editForm = $this->createForm(UserType::class, $user, array('role'=>$user->getRoleId()));
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('user_manager');
        }

        return $this->render('@basic/security/edit_user.html.twig', [
            'user' => $user,
            'edit_form' => $editForm->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param User $user
     * @Route("{id}/delete_user",name="user_delete")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function userDeleteAction(Request $request, User $user)
    {
        $user->setPlainPassword('~~~~~~~~~');
        $deleteForm = $this->createForm(UserType::class, $user,array('role'=>$user->getRoleId()));
        $deleteForm->handleRequest($request);

        if ($deleteForm->isSubmitted() && $deleteForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();

            return $this->redirectToRoute('user_manager');
        }
        return $this->render('@basic/security/delete_user.html.twig', [
            'user' => $user,
            'delete_form' => $deleteForm->createView()
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/activate_user/{id}",name="activate_user")
     * @Method("GET")
     * @param User $user
     */
    public function sendEmailAction(User $user)
    {
        $user->setIsActive(1);
        $em = $this->getDoctrine()->getManager();
        $em->flush();
        return $this->redirectToRoute('login');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @param Request $request
     * @Route("/forgot_password",name="forgot_password")
     */
    public function forgotPasswordAction(Request $request){
        $validate= new ForgotPassword();
        $form = $this->createForm(ForgotPasswordType::class,$validate);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $userService = $this->get(UserService::class);
            $userService->forgotPassword($validate);
            return $this->redirectToRoute('login');
        }
        return $this->render("@basic/security/forgot_password.html.twig",array(
            'form'=>$form->createView()
        ));
    }
}
