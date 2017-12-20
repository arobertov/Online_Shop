<?php
/**
 * Created by PhpStorm.
 * User: Angel
 * Date: 19.12.2017 г.
 * Time: 21:42
 */

namespace ShopBundle\Services;


use Doctrine\ORM\EntityManagerInterface;
use ShopBundle\Entity\Promotion;
use ShopBundle\Repository\PromotionRepository;


class PromotionService implements PromotionServiceInterface {

	/**
	 * @var EntityManagerInterface $em
	 */
	private $em;

	/**
	 * @var PromotionRepository $promotionRepository
	 */
	private $promotionRepository;

	/**
	 * PromotionService constructor.
	 *
	 * @param EntityManagerInterface $em
	 * @param PromotionRepository $promotionRepository
	 */
	public function __construct( EntityManagerInterface $em, PromotionRepository $promotionRepository ) {
		$this->em                  = $em;
		$this->promotionRepository = $promotionRepository;
	}

	/**
	 * @param array $ids
	 */
	private function updatePromotionIsActiveDate( Array $ids ) {
		$this->promotionRepository->updatePromotionSetIsActive( $ids );
	}

	/**
	 * @param array $ids
	 */
	private function updatePromotionIsInactiveDate( Array $ids ) {
		$this->promotionRepository->updatePromotionSetIsInactive( $ids );
	}


	public function checkPromotionDate() {
		$promotionsDate = $this->promotionRepository->findAll();
		$currentDate    = new \DateTime( 'now' );
		$activeIds      = array();
		$inactiveIds    = array();

		foreach ( $promotionsDate as $promotionDate ) {
			/** @var Promotion $promotionDate */
			$id = $promotionDate->getId();
			if ( $currentDate > $promotionDate->getDueDate() && $currentDate < $promotionDate->getEndDate() ) {
				$activeIds[] = $id;
			} else {
				$inactiveIds[] = $id;
			}
		}
		$this->updatePromotionIsActiveDate( $activeIds );

		$this->updatePromotionIsInactiveDate( $inactiveIds );
	}

	/**
	 * @return array
	 */
	public function findInactivePromotion(){
		return $this->promotionRepository->findBy(['isActive'=>0]);
	}
}