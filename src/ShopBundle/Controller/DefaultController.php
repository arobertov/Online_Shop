<?php

namespace ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
	/**
	 * @Route("/",name="home_page")
	 */
	public function indexAction()
	{
		return $this->render('@Shop/Default/blank.html.twig');
	}
}
