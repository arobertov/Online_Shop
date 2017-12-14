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
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/create_product",name="create_product")
     *
     */
    public function createProductAction(Request $request)
    {
        $product = new Product();
        $productUsers = new ProductUsers();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $product->setDateCreated(new \DateTime('now'));
            $product->addProductToUser($productUsers);

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();
            return $this->redirectToRoute('product_list');
        }
        return $this->render('@Shop/product/create_product.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/products",name="product_list")
     */
    public function listAllProductAction()
    {
        $em = $this->getDoctrine()->getManager();
        $products = $em->getRepository('ShopBundle:Product')->findAllWithJoin();
        return $this->render('@Shop/product/product_list.html.twig', array(
            'products' => $products
        ));
    }

    /**
     * @param $id
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/{id}/view_product",name="view_product")
     */
    public function productDetails($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('ShopBundle:Product')->findOneWithJoin($id);
        $addForm = $this->createForm(ProductType::class, $product);
        $addForm->handleRequest($request);

        if ($addForm->isSubmitted() && $addForm->isValid()) {
            $cartSession = $this->get(CartSession::class);
            $cartSession->setCartSession($product);
            return $this->redirectToRoute('personal_cart');
        }
        return $this->render('@Shop/product/product_details.html.twig', array(
            'product' => $product,
            'cart_form' => $addForm->createView()
        ));

    }


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
