<?php

namespace ShopBundle\Repository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * OrdersRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class OrderRepository extends \Doctrine\ORM\EntityRepository
{
	/**
	 * @var EntityManagerInterface
	 */
	private $em;

	/**
	 * OrderRepository constructor.
	 *
	 * @param EntityManagerInterface $em
	 */
	public function __construct( EntityManagerInterface $em ) {
		$this->em = $em;
	}

	public function findAllOrdersJoinProducts(){
		$query = $this->em->createQuery('SELECT o , pu , u 
											  FROM ShopBundle:Order o 
											  JOIN o.productUsers pu
											  JOIN o.user u'
		);
		return $query->getResult();
	}

	public function findOneOrderJoinProduct($id){
		$query = $this->em->createQuery('SELECT o , pu , u 
											  FROM ShopBundle:Order o 
											  JOIN o.productUsers pu
											  JOIN o.user u
											  WHERE o.id = :id'
		);
		$query->setParameter('id',$id);
		try{
			return $query->getSingleResult();
		}   catch (\Doctrine\ORM\NoResultException $e) {
			return null;
		}
	}
}
