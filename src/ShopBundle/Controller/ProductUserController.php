<?php

namespace ShopBundle\Controller;

use AppBundle\Entity\User;
use Doctrine\ORM\NonUniqueResultException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use ShopBundle\Entity\ProductUsers;
use ShopBundle\Form\OrderViewType;
use ShopBundle\Form\ProductUsersType;
use ShopBundle\Services\CartSessionService;
use ShopBundle\Services\ProductServiceInterface;
use ShopBundle\Services\ProductUsersInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductUserController extends Controller {

	/**
	 * @var ProductUsersInterface $productUserService
	 */
	private $productUserService;

	/**
	 * @var ProductServiceInterface $productService
	 */
	private $productService;

	/**
	 * ProductUserController constructor.
	 *
	 * @param ProductUsersInterface $productUserService
	 * @param ProductServiceInterface $productService
	 */
	public function __construct( ProductUsersInterface $productUserService,ProductServiceInterface $productService ) {
		$this->productUserService = $productUserService;
		$this->productService = $productService;
	}


	/**
	 * @param Request $request
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 * @Security("has_role('ROLE_EDITOR')")
	 * @Route("new_products",name="new_products_users")
	 * @throws \Doctrine\ORM\NonUniqueResultException
	 */
	public function createNewProductAction( Request $request ) {
		$productUsers = new ProductUsers();
		$form         = $this->createForm( ProductUsersType::class, $productUsers );
		$form->handleRequest( $request );

		if ( $form->isSubmitted() && $form->isValid() ) {
			$productUsers->getProduct()->setDateCreated( new \DateTime( 'now' ) );

			$em = $this->getDoctrine()->getManager();
			//-- find super admin user and set product ownership
			$superAdminUser = $em->getRepository( User::class )->findSuperAdminUser();
			$productUsers->setUser( $superAdminUser );
			$productUsers = $this->productService->uploadedFile($productUsers);
			$em->persist( $productUsers );
			$em->flush();

			return $this->redirectToRoute( 'home_page' );
		}

		return $this->render( '@Shop/product_users/new_product.html.twig', array(
			'form' => $form->createView()
		) );
	}

	/**
	 * @param Request $request
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 *
	 * @Route("/",name="home_page")
	 */
	public function indexAction(Request $request) {
		$companyProducts = $this->productUserService->listAllCompanyProducts();
		$form = $this->createForm(OrderViewType::class);
		$form->handleRequest($request);
		if($form->isSubmitted()){
			$criteria = $this->productUserService->addCriteria($form->getData());
			$companyProducts = $this->productUserService->listAllCompanyProducts($criteria);
		}
		return $this->render( '@Shop/product_users/product_list.html.twig', array(
			'products' => $companyProducts,
			'form'=>$form->createView()
		) );

	}

	/**
	 * @param Request $request
	 *
	 * @return Response
	 *
	 * @Route("/users_products",name="users_products")
	 */
	public function listAllUsersProducts(Request $request){
		$userProducts = $this->productUserService->listAllCompanyProducts();
		$form = $this->createForm(OrderViewType::class);
		$form->handleRequest($request);
		if($form->isSubmitted()){

			$criteria = $this->productUserService->addCriteria($form->getData());
			$userProducts = $this->productUserService->listAllCompanyProducts($criteria);
		}
		return $this->render('@Shop/product_users/product_list.html.twig',array(
			'products'=>$userProducts,
			'form'=>$form->createView()
		));
	}

	/**
	 * @param $id
	 *
	 * @param Request $request
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 * @Route("/{id}/category",name="product_category")
	 */
	public function listByCategory($id,Request $request){
		$products = $this->productUserService->listProductByCategory($id);
		$form = $this->createForm(OrderViewType::class);
		$form->handleRequest($request);
		if($form->isSubmitted()){
			$criteria = $this->productUserService->addCriteria($form->getData());
			$products = $this->productUserService->listProductByCategory($id , $criteria);
		}
		return $this->render('@Shop/product_users/product_list.html.twig',array(
			'products'=>$products,
			'form'=>$form->createView()
		));
	}

	/**
	 * @param Request $request
	 * @param $id
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 * @Route("/{id}/show_product",name="show_product")
	 * @throws \Doctrine\ORM\NonUniqueResultException
	 */
	public function showAction( Request $request, $id ) {
		$em                            = $this->getDoctrine()->getManager();
		$productUser                   = $em->getRepository( 'ShopBundle:ProductUsers' )->findOneProduct( $id );
		ProductUsersType::$addQuantity = true;
		$form                          = $this->createForm( ProductUsersType::class, $productUser );
		$form->handleRequest( $request );
		if ( $form->isSubmitted() && $form->isValid() ) {
			$cartSession = $this->get(CartSessionService::class);
			$cartSession->setCartSession($productUser);
			return $this->redirectToRoute( 'home_page');
		}

		return $this->render( '@Shop/product_users/show.html.twig', array(
			'product' => $productUser,
			'form'    => $form->createView()
		) );
	}

	/**
	 * @param Request $request
	 * @param $id
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 * @Route("/{id}/edit_product",name="edit_product")
	 * @throws \Doctrine\ORM\NonUniqueResultException
	 */
	public function editAction( Request $request, $id ) {
		$em      = $this->getDoctrine()->getManager();
		/** @var ProductUsers $product */
		$product = $em->getRepository( 'ShopBundle:ProductUsers' )->findOneProduct($id);
		if ( $this->getUser()->getRoles() === [ 'ROLE_USER' ] ) {
			//-- This static property deny render product form,render product user form change price
			ProductUsersType::$userRestrict = true;
		}
		$oldPath = $product->getProduct()->getImage();
		$product->getProduct()->setImage( null);

		$form = $this->createForm( ProductUsersType::class, $product );
		$form->handleRequest($request);
		if ( $form->isSubmitted() && $form->isValid() ) {
			if($product->getProduct()->getImage() === null){
				$product->getProduct()->setImage($oldPath);
			} else $product = $this->productService->uploadedFile($product);

			$em->flush();
			return $this->redirectToRoute( 'home_page' );
		}

		return $this->render( '@Shop/product_users/edit_product.html.twig', array(
			'product' => $product,
			'form'    => $form->createView()
		) );

	}

	/**
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
	 *
	 * @param $id
	 * @param Request $request
	 * @Security("has_role('ROLE_EDITOR')")
	 * @Route("/{id}/delete_product",name="delete_product")
	 */
	public function deleteAction($id,Request $request){
		$em      = $this->getDoctrine()->getManager();
		try {
			$product = $em->getRepository( 'ShopBundle:ProductUsers' )->findOneProduct( $id );
		} catch ( NonUniqueResultException $e ) {
		}
		$form = $this->createForm( ProductUsersType::class, $product );
		$form->handleRequest( $request );

		if ( $form->isSubmitted() && $form->isValid() ) {
			$em->remove($product);
			$em->flush();
			return $this->redirectToRoute('home_page');
		}
		return $this->render('@Shop/product_users/delete_product.html.twig',array(
			'form'=>$form->createView()
		));
	}

	/**
	 * @Security("has_role('ROLE_EDITOR')")
	 * @Route("/{id}/set_promotion",name="set_promotion")
	 * @param $id
	 *
	 * @return Response
	 */
	public function setProductPromotion($id) {

		$result = $this->productUserService->addPromotionByCategory( $id );

		return $this->render( '@Shop/product_users/set_promotion.html.twig', array(
			'result' => $result
		) );
	}

}
