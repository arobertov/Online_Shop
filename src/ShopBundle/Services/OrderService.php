<?php
/**
 * Created by PhpStorm.
 * User: AngelRobertov
 * Date: 13.12.2017 г.
 * Time: 19:30
 */

namespace ShopBundle\Services;


use AppBundle\Entity\User;
use AppBundle\Entity\UserAddress;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NoResultException;
use ShopBundle\Entity\Order;
use ShopBundle\Entity\Product;
use ShopBundle\Entity\ProductUsers;
use ShopBundle\Entity\Promotion;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Validator\Constraints\DateTime;

class OrderService implements OrderServiceInterface {

	/**
	 * @var  EntityManagerInterface
	 */
	private $em;


	public function __construct( EntityManagerInterface $em ) {
		$this->em = $em;
	}

	/**
	 * @param $userId
	 *
	 * @return \AppBundle\Entity\User|null|object
	 */
	public function findUser( $userId ) {
		return $this->em->getRepository( 'AppBundle:User' )->findOneBy( [ 'id' => $userId ] );
	}

	/**
	 * @param Order $order
	 * @param User $user
	 *
	 * @param Request $request
	 *
	 * @return  integer|null
	 */
	public function createOrder( Order $order, User $user = null, Request $request ): ?integer {
		$session = new Session();

		$order->setProductUsers( $this->createProductsOrder( $user ) );
		$order->setTotalAmount( $session->get( 'total' ) );
		$order->setOrderDate( new \DateTime( 'now' ) );
		$order->setClientIpAddress( $request->getClientIp() );
		$order->setStatus( 'Unconfirmed' );
		$order->setUser( $user );

		$this->em->persist( $order );
		$this->em->flush();

		try {
			return $order->getId();
		} catch ( Exception $e ) {
			return null;
		}

	}


	/**
	 * @param $user
	 *
	 * @return array|null
	 */
	private function createProductsOrder( User $user = null ): ?array {
		$session  = new Session();
		$products = array();
		if ( $session->has( 'cart' ) ) {
			foreach ( $session->get( 'cart' ) as $product ) {
				/** @var ProductUsers $product ,$productToSell */
				$productToSell =
					$this->em->getRepository( 'ShopBundle:ProductUsers' )
					         ->findOneBy( [ 'id' => $product->getId() ] );
				if ( null !== $productToSell->getQuantity() > 0 && $productToSell->getQuantity() >= $product->getQuantity() ) {
					$productToSell->setQuantity( $productToSell->getQuantity() - $product->getQuantity() );
				}
				$product->getQuantity();
				$product->setPromotion(
					$this->em->getRepository( 'ShopBundle:Promotion' )
					         ->findOneBy( [ 'id' => ! $product->getPromotion() ? null : $product->getPromotion()->getId() ] )
				);
				$product->setProduct(
					$this->em->getRepository( 'ShopBundle:Product' )
					         ->findOneBy( [ 'id' => $product->getProduct()->getId() ] )
				);
				$product->setUser( $user );
				$productUsers = $this->checkProductToExistInUser( $product, $user );
				if ( ( null !== $productUsers && $product->isHasSell() == false ) || null === $productUsers ) {
					$this->em->persist( $product );
					$products[] = $product;
				} else {
					$product->setQuantity( $productUsers->getQuantity() + $product->getQuantity() );
					$products[] = $productUsers;
				}
				$this->em->flush();
			}

			return $products;
		}

		return null;
	}

	/**
	 * @param ProductUsers $productUsers
	 * @param User $user
	 *
	 * @return null|object|ProductUsers
	 */
	private function checkProductToExistInUser( ProductUsers $productUsers, User $user = null ): ? Product {
		$product = $this->em->getRepository( 'ShopBundle:ProductUsers' )
		                    ->findOneBy( [ 'product' => $productUsers->getProduct(), 'user' => $user ] );
		if ( null !== $product ) {
			return $product;
		}

		return null;
	}

	/**
	 * @param Order $order
	 * @param User|null $user
	 *
	 * @return Order
	 * @throws \Doctrine\ORM\NonUniqueResultException
	 */
	public function setOrderItemWithUserRegister( Order $order, User $user = null ): Order {
		/** @var User $user */
		$user = $this->em->getRepository( 'AppBundle:User' )->findUserJoinAddress( $user->getId() );
		/** @var UserAddress $address */
		$address = $user->getAddress();
		$order->setFirstName( $user->getFirstName() );
		$order->setLastName( $user->getLastName() );
		$order->setOrderEmail( $user->getEmail() );
		$order->setShipCity( $address->getCity() );
		$order->setShipAddress( $address->getShipAddress() );
		$order->setOrderPhone( $address->getPhoneNumber() );

		return $order;
	}
}