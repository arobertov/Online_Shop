<?php

namespace ShopBundle\Repository;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use ShopBundle\Entity\Promotion;

/**
 * PromotionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PromotionRepository extends \Doctrine\ORM\EntityRepository
{
	/**
	 * @var EntityManagerInterface $em
	 */
	private $em;

	/**
	 * PromotionRepository constructor.
	 *
	 * @param EntityManagerInterface $em
	 * @param ClassMetadata $class
	 */
	public function __construct( EntityManagerInterface $em ,ClassMetadata $class = null) {
		parent::__construct( $em,new ClassMetadata(Promotion::class));
		$this->em = $em;
	}

	public function updatePromotionSetIsActive($ids){
		$query = $this->em->createQuery('UPDATE ShopBundle\Entity\Promotion p 
											 SET p.isActive = 1 
											 WHERE p.id IN ( :ids )'
		);
		$query->setParameter('ids',$ids);
		$query->getResult();
	}

	public function updatePromotionSetIsInactive($ids){
		$query = $this->em->createQuery('UPDATE ShopBundle\Entity\Promotion p 
											 SET p.isActive = 0 
											 WHERE p.id IN ( :ids )'
		);
		$query->setParameter('ids',$ids);
		$query->getResult();
	}

	public function checkBiggerPromotionDiscount($discount){
		$query = $this->em->createQuery('SELECT p.id
											  FROM ShopBundle\Entity\Promotion p
											  INDEX BY p.id 
											  WHERE p.discount <= :discount'
		)->setParameter('discount',$discount)
		;
		return $query->getResult();
	}
}
