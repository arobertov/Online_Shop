<?php

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;



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

    use \Sideclick\BootstrapModalBundle\Controller\ControllerTrait;
    public function thisActionWillRedirect(Request $request)
    {
        return $this->redirectWithAjaxSupport($request, 'login');
    }



    public function thisActionWillReload(Request $request)
    {
        $authUtils = $this->get('security.authentication_utils');
        $error = $authUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authUtils->getLastUsername();

        return $this->redirectToRouteWithAjaxSupport($request,'login',['error'=>$error]);
    }
}
