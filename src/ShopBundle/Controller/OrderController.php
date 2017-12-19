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
		$user = $this->getUser();
		if(isset($user)){
			$order = $orderService->setOrderItemWithUserRegister($order,$user);
		}
		$form   = $this->createForm( 'ShopBundle\Form\OrderType', $order );
		$form->handleRequest( $request );

		if ( $form->isSubmitted() && $form->isValid() ) {

			$id = $orderService->createOrder( $order, $user, $request );
			if (null !== $order->getUser()->getId()) {
				$uId = $order->getUser()->getId();
			}
		  return $this->redirectToRoute('order_show',array(
		  	 'id'=>$id ,
			 'uid'=>$uId
		  ));
		}

		return $this->render( '@Shop/order/create_order.html.twig', array(
			'order'=>$order,
			'form' => $form->createView()
		) );
	}

	/**
	 *
	 * @param $id
	 *
	 * @param Request $request
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 *
	 * @Route("/{id}/order_preview",name="order_show")
	 */
	public function showMyOrder($id, Request $request){
		$uId = $request->get('uid');
		$orderService = $this->get( OrderProductService::class );
		$em = $this->getDoctrine()->getManager();
		if(isset($uId)){
			$order = $em->getRepository(Order::class)->findOneOrderJoinProducts($id);
		} else
			$order = $em->getRepository(Order::class)->findOneOrderUserIsNullJoinProducts($id);
		return $this->render('@Shop/order/order_details.html.twig',array(
			'order'=>$order
		));
	}
}
