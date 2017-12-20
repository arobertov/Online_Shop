<?php

namespace ShopBundle\Controller;

use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use ShopBundle\Entity\Product;
use ShopBundle\Entity\ProductCategory;
use ShopBundle\Entity\ProductUsers;
use ShopBundle\Entity\Promotion;
use ShopBundle\Form\ProductUsersType;
use ShopBundle\Services\CartSessionService;
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
	 * ProductUserController constructor.
	 *
	 * @param ProductUsersInterface $productUserService
	 */
	public function __construct( ProductUsersInterface $productUserService ) {
		$this->productUserService = $productUserService;
	}


	/**
	 * @param Request $request
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 *
	 * @Route("new_products",name="new_products_users")
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
			$em->persist( $productUsers );
			$em->flush();

			return $this->redirectToRoute( 'home_page' );
		}

		return $this->render( '@Shop/product_users/new_product.html.twig', array(
			'form' => $form->createView()
		) );
	}

	/**
	 * @return \Symfony\Component\HttpFoundation\Response
	 *
	 * @Route("/",name="home_page")
	 */
	public function indexAction() {
		$em              = $this->getDoctrine()->getManager();
		$companyProducts = $em->getRepository( 'ShopBundle:ProductUsers' )->findAllCompanyProducts();
		$userProducts    = $em->getRepository( 'ShopBundle:ProductUsers' )->findAllUserProducts();

		return $this->render( '@Shop/product_users/product_list.html.twig', array(
			'companyProducts' => $companyProducts,
			'userProducts'    => $userProducts
		) );

	}

	/**
	 * @param Request $request
	 * @param $id
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 * @Route("/{id}/show_product",name="show_product")
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
	 * 
	 * @Route("/{id}/edit_product",name="edit_product")
	 */
	public function editAction( Request $request, $id ) {
		$em      = $this->getDoctrine()->getManager();
		$product = $em->getRepository( 'ShopBundle:ProductUsers' )->findOneProduct( $id );
		if ( $this->getUser()->getRoles() === [ 'ROLE_USER' ] ) {
			//-- This static property deny render product form,render product user form change price
			ProductUsersType::$userRestrict = true;
		}
		$form = $this->createForm( ProductUsersType::class, $product );
		$form->handleRequest( $request );

		if ( $form->isSubmitted() && $form->isValid() ) {
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
	 * @param $id
	 * @param Request $request
	 *
	 * @Route("/{id}/delete_product",name="delete_product")
	 */
	public function deleteAction($id,Request $request){
		$em      = $this->getDoctrine()->getManager();
		$product = $em->getRepository( 'ShopBundle:ProductUsers' )->findOneProduct( $id );
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
	 * @Route("/set_promotion",name="set_promotion")
	 * @return Response
	 */
	public function setProductPromotion(){

		$result = $this->productUserService->addPromotionByCategory(2);
		return  $this->render('@Shop/product_users/set_promotion.html.twig',array(
			 'result'=> $result
		)) ;
	}
}
