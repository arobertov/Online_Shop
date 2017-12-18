<?php
/**
 * Created by PhpStorm.
 * User: AngelRobertov
 * Date: 13.12.2017 г.
 * Time: 19:30
 */

namespace ShopBundle\Services;


use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use ShopBundle\Entity\Order;
use ShopBundle\Entity\ProductUsers;
use ShopBundle\Entity\Promotion;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Validator\Constraints\DateTime;

class OrderProductService {

	/**
	 * @var  EntityManagerInterface
	 */
	private $em;

	/**
	 * @var ProductUsers
	 */
	private $productUser;

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
	 * @return  integer
	 */
	public function createOrder( Order $order, User $user ) {
		$session = new Session();
		$order->setProductUsers( $this->createProductsOrder( $user ) );
		$order->setTotalAmount( $session->get( 'total' ) );
		$order->setOrderDate( new \DateTime( 'now' ) );
		$order->setUser( $user );

		$this->em->persist( $order );
		$this->em->flush();

		return $order->getId();
	}

	/**
	 * @param $user
	 *
	 * @return array|null
	 */
	private function createProductsOrder( User $user ) {
		$session  = new Session();
		$products = array();
		if ( $session->has( 'cart' ) ) {
			foreach ( $session->get( 'cart' ) as $product ) {
				/** @var ProductUsers $product */
				$product->setPromotion(
					$this->em->getRepository( 'ShopBundle:Promotion' )->findOneBy( [ 'id' => $product->getPromotion()->getId() ] )
				);
				$product->setProduct(
					$this->em->getRepository( 'ShopBundle:Product' )->findOneBy( [ 'id' => $product->getProduct()->getId() ] )
				);
				$product->setUser( $user );
				if( null === $productUser = $this->checkProductToExistInUser( $product, $user )){
					$this->em->persist( $product );
					$products[] = $product;
				} else {
					$products[]= $productUser;
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
	private function checkProductToExistInUser( ProductUsers $productUsers, User $user ) {
		$product = $this->em->getRepository( 'ShopBundle:ProductUsers' )
		                    ->findOneBy( [ 'product' => $productUsers->getProduct(), 'user' => $user ] );
		if(null !== $product){
			$product->setQuantity( $productUsers->getQuantity() + $product->getQuantity() );
			return $product;
		}
		return null;
	}



	public function createUserProduct( $user ) {


	}
}