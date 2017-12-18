<?php

namespace ShopBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ShopBundle\Entity\Order;
use ShopBundle\Services\OrderProductService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


/**
 * Class OrderController
 * @package ShopBundle\Controller
 * @Route("/order")
 */
class OrderController extends Controller {
	/**
	 * @param Request $request
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 * @Route("/create_order",name="add_order")
	 */
	public function createOrderAction( Request $request ) {
		$order = new Order();
		$orderService = $this->get( OrderProductService::class );
		//************** //
		if($user = $this->getUser()){
			$order = $orderService->createUserProduct($user);
		}
		$form   = $this->createForm( 'ShopBundle\Form\OrderType', $order );
		$form->handleRequest( $request );
		if ( $form->isSubmitted() && $form->isValid() ) {

			$id = $orderService->createOrder( $order, $user );

		  return $this->redirectToRoute('order_show',array(
		  	'id'=>$id
		  ));
		}

		return $this->render( '@Shop/order/create_order.html.twig', array(
			'form' => $form->createView()
		) );
	}

	/**
	 *
	 * @param $id
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 *
	 * @Route("/{id}/order_preview",name="order_show")
	 */
	public function showMyOrder($id){
		$orderService = $this->get( OrderProductService::class );
		$orderService->createUserProduct($this->getUser());
		$em = $this->getDoctrine()->getManager();
		$order = $em->getRepository(Order::class)->findAllOrdersJoinProducts();
		return $this->render('@Shop/order/order_details.html.twig',array(
			'order'=>$order
		));
	}
}
