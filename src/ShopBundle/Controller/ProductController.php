<?php

namespace ShopBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ShopBundle\Entity\Cart;
use ShopBundle\Entity\Product;
use ShopBundle\Entity\ProductUsers;
use ShopBundle\Form\CartType;
use ShopBundle\Form\ProductType;
use ShopBundle\Repository\ProductRepository;
use ShopBundle\Services\CartSession;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class ProductController
 * @package ShopBundle\Controller
 * @Route("/product")
 */
class ProductController extends Controller
{


    /**
     *
     * @Route("/personal_cart",name="personal_cart")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addProductsToCartAction()
    {
        $setProductTotal = $this->get(CartSession::class);
        $setProductTotal->setProductTotal();
        return $this->render('@Shop/product/personal_cart.html.twig');
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/{id}/session_clear",name="session_clear")
     */
    public function clearCart($id)
    {
        $session = new Session();
        if (count($session->get('cart')) > 0) {
	        $arr = $session->get('cart');
	        unset($arr[$id]);
	        $session->set('cart', $arr);
            }
        return $this->redirectToRoute('personal_cart');
    }
}
