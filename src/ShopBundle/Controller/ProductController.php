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
use ShopBundle\Services\CartSessionService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class ProductController
 * @package ShopBundle\Controller
 *
 * @Route("/product")
 */
class ProductController extends Controller
{
	/**
	 * @return \Symfony\Component\HttpFoundation\Response
	 * @Route("/all_products",name="all_products")
	 */
	public function indexAction(){
		$em = $this->getDoctrine()->getManager();
		$products = $em->getRepository(Product::class)->findAll();
		return $this->render('@Shop/product/list_all_products.html.twig',array(
			'products'=> $products
		));
	}

	/**
	 * @param Product $product
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 * @Route("/{id}/preview_product",name="show_product_template")
	 */
	public function showAction(Product $product){
		return $this->render('@Shop/product/show_product.html.twig',array(
			'product'=>$product
		));
	}

	/**
	 * @param Product $product
	 * @param Request $request
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 * @Route("/{id}/edit_product",name="edit_product_template")
	 */
	public function editAction(Product $product,Request $request){
	   $form = $this->createForm(ProductType::class,$product);
	   $form->handleRequest($request);

	   if($form->isSubmitted() && $form->isValid()){
	   	    $this->getDoctrine()->getManager()->flush();

	   	    return $this->redirectToRoute('all_products');
	   }
	   return $this->render('@Shop/product/edit_product.html.twig',array(
	   	    'edit_form'=>$form->createView()
	   ));
	}

	/**
	 * @param Product $product
	 * @param Request $request
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 * @Route("/{id}/delete_product",name="delete_product_template")
	 */
	public function deleteAction(Product $product,Request $request){
		$form = $this->createForm(ProductType::class,$product);
		$form->handleRequest($request);
		$em = $this->getDoctrine()->getManager();
		if($form->isSubmitted() && $form->isValid()){
		 	$em->remove($product);
		 	$em->flush();
		 	$this->redirectToRoute('all_products') ;
		}
		return $this->render('@Shop/product/delete_product.html.twig',array(
			'delete_form'=>$form->createView()
		));
	}

}
