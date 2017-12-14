<?php

namespace ShopBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ShopBundle\Entity\Orders;
use ShopBundle\Services\OrderProductService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


/**
 * Class OrderController
 * @package ShopBundle\Controller
 * @Route("/order")
 */
class OrderController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/create_order",name="add_order")
     */
    public function createOrderAction(Request $request)
    {
        $orders = new Orders();
        $form = $this->createForm('ShopBundle\Form\OrderType',$orders);
        $form->handleRequest($request);

        if($this->getUser()){
           $orderService = $this->get(OrderProductService::class);
           $user = $orderService->findUser($this->getUser()->getId());
        }
        return $this->render('@Shop/order/create_order.html.twig',array(
            'user'=>$user,
            'form'=>$form->createView()
        ));
    }
}
