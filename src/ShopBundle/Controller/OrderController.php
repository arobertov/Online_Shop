<?php

namespace ShopBundle\Controller;

use Doctrine\ORM\NonUniqueResultException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ShopBundle\Entity\Order;
use ShopBundle\Repository\OrderRepository;
use ShopBundle\Services\CartSessionService;
use ShopBundle\Services\OrderServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;


/**
 * Class OrderController
 * @package ShopBundle\Controller
 * @Route("/order")
 */
class OrderController extends Controller {

	/**
	 * @var OrderRepository $orderRepository
	 */
	private $orderRepository;

	/**
	 * @var OrderServiceInterface $orderService
	 */
	private $orderService;

	/**
	 * OrderController constructor.
	 *
	 * @param OrderRepository $orderRepository
	 * @param OrderServiceInterface $orderService
	 */
	public function __construct( OrderRepository $orderRepository ,OrderServiceInterface $orderService) {
		$this->orderRepository = $orderRepository;
		$this->orderService = $orderService;
	}


	/**
	 * @return \Symfony\Component\HttpFoundation\Response
	 *
	 * @Route("/",name="order_index")
	 */
	public function indexOrderAction(){

		$orders = $this->orderRepository->findAllOrders();
		return $this->render('@Shop/order/list_orders.html.twig',array(
			'orders'=>$orders
		));

	}

	/**
	 * @param Request $request
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 * @Route("/create_order",name="add_order")
	 *
	 */
	public function createOrderAction( Request $request ) {
		$order = new Order();
		$user = $this->getUser();
		if(isset($user)){
			try {
				$order = $this->orderService->setOrderItemWithUserRegister( $order, $user );
			} catch ( NonUniqueResultException $e ) {
			}
		}
		$form   = $this->createForm( 'ShopBundle\Form\OrderType', $order );
		$form->handleRequest( $request );

		if ( $form->isSubmitted() && $form->isValid() ) {
			$id = $this->orderService->createOrder( $order, $user, $request );
		  return $this->redirectToRoute('order_show',array(
		  	 'id'=>$id ,
			 'uid'=> null === $order->getUser() ? null: $order->getUser()->getId()
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
		$em = $this->getDoctrine()->getManager();
		if(isset($uId)){
			$order = $this->orderRepository->findOneOrderJoinProducts($id);
		} else
			$order = $this->orderRepository->findOneOrderUserIsNullJoinProducts($id);
		return $this->render('@Shop/order/order_details.html.twig',array(
			'order'=>$order
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
		$setProductTotal = $this->get(CartSessionService::class);
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
			if(count($session->get('cart')) == 1){
				$session->remove('total');
			}
			$arr = $session->get('cart');
			unset($arr[$id]);
			$session->set('cart', $arr);
		}
		return $this->redirectToRoute('personal_cart');
	}
}
