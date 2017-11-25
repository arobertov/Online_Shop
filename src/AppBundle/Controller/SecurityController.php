<?php

namespace AppBundle\Controller;

use AppBundle\Entity\UserEdit;
use AppBundle\Entity\User;
use AppBundle\Form\UserEditType;
use AppBundle\Form\UserType;
use AppBundle\Services\UserService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


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


}
