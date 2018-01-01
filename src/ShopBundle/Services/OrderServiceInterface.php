<?php
/**
 * Created by PhpStorm.
 * User: Angel
 * Date: 25.12.2017 г.
 * Time: 3:02
 */

namespace ShopBundle\Services;


use AppBundle\Entity\User;
use ShopBundle\Entity\Order;
use Symfony\Component\HttpFoundation\Request;

interface OrderServiceInterface {

	public function findUser( $userId );

	public function createOrder( Order $order, User $user = null ,Request $request);

	public function setOrderItemWithUserRegister( Order $order, User $user = null);
}