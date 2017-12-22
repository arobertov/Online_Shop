<?php
/**
 * Created by PhpStorm.
 * User: Angel
 * Date: 3.12.2017 г.
 * Time: 17:17
 */

namespace ShopBundle\Services;


use ShopBundle\Entity\Product;
use ShopBundle\Entity\ProductUsers;
use Symfony\Component\HttpFoundation\Session\Session;

class CartSessionService {
	/**
	 * @param ProductUsers $product
	 */
	public function setCartSession( ProductUsers $product ) {
		$session = new Session();
		if ( $session->has( 'cart' ) ) {
			$products = $session->get( 'cart' );

			if ( array_key_exists( $product->getId(), $products ) ) {

				/** @var ProductUsers $oldValue */
				$oldValue = $products[ $product->getId() ];

				$newValue = $oldValue->getQuantity() + $product->getQuantity();
				$product->setQuantity( $newValue );
				$products[ $product->getId() ] = $product;

			} else {
				$products[ $product->getId() ] = $product;
			}
		} else {
			$products[ $product->getId() ] = $product;
		}
		$session->set( 'cart', $products );
	}

	public function setProductTotal() {
		$session = new Session();
		if ( count( $session->get( 'cart' ) ) > 0 ) {
			$products      = $session->get( 'cart' );
			$productsTotal = null;
			foreach ( $products as $key => $value ) {
				/** @var ProductUsers $product */
				$product = $value;
				// set subtotal
				if ( $product->getPromotion() !== null ) {
					$productDiscount =
						( $product->getPrice() - ( $product->getPrice() * ( $product->getPromotion()->getDiscount() ) ) );
					$subtotal = (floatval($product->getQuantity()) * $productDiscount);
				} else {
					$subtotal = (floatval($product->getQuantity()) * $product->getPrice());
				}
				
				$product->getProduct()->setSubtotal( $subtotal );
				$products[ $key ] = $product;
				$productsTotal    += $subtotal;
			}
			$session->set( 'total', $productsTotal );
		}
	}
}