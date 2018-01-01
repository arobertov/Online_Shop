<?php

namespace ShopBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ShopBundle\Entity\Product;
use ShopBundle\Form\ProductType;
use ShopBundle\Services\ProductServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ProductController
 * @package ShopBundle\Controller
 *
 * @Route("/product")
 */
class ProductController extends Controller
{
	/**
	 * @var EntityManagerInterface $em
	 */
	private $em;

	/**
	 * @var ProductServiceInterface $productService
	 */
	private $productService;

	/**
	 * ProductController constructor.
	 *
	 * @param EntityManagerInterface $em
	 */
	public function __construct( EntityManagerInterface $em ,ProductServiceInterface $product_service ) {
		$this->em = $em;
		$this->productService = $product_service;
	}


	/**
	 * @return \Symfony\Component\HttpFoundation\Response
	 * @Route("/all_products",name="all_products")
	 */
	public function indexAction(){
		$products = $this->em->getRepository(Product::class)->findAll();
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
		if($form->isSubmitted() && $form->isValid()){
		 	$this->em->remove($product);
		 	$this->em->flush();
		 	$this->redirectToRoute('all_products') ;
		}
		return $this->render('@Shop/product/delete_product.html.twig',array(
			'delete_form'=>$form->createView()
		));
	}

}
