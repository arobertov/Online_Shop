<?php

namespace ShopBundle\Repository;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\NoResultException;
use ShopBundle\Entity\ProductUsers;

/**
 * productUsersRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProductUsersRepository extends EntityRepository
{

	/**
	 * @var EntityManagerInterface $em
	 */
	private $em;



	/**
	 * ProductUsersRepository constructor.
	 *
	 * @param EntityManagerInterface $em
	 */
	public function __construct(EntityManagerInterface $em ) {
		parent::__construct( $em , new ClassMetadata(ProductUsers::class) );
		$this->em = $em;
	}

	/**
	 * @return integer|null
	 */
	private function getSuperAdminId() {
		if(null !== $superAdmin = $this->em->getRepository(User::class)->findRoleUser(['name'=>'ROLE_SUPER_ADMIN'])){
			return  $superAdmin->getId();
		}
		return null;
	}

	/**
	 *
	 * @param null $criteria
	 * @param int $offset
	 * @param int $limit
	 *
	 * @return mixed
	 * @throws \Doctrine\ORM\NonUniqueResultException
	 */
	public function findAllCompanyProducts($criteria = null,$offset = 0,$limit = 2){
		$cr = array('p.dateCreated','ASC');
		if(null !== $criteria){
			$cr = $criteria;
		}
		$db = $this->em->createQueryBuilder();
		$db
			->select('pu','p','pr')
			->from('ShopBundle:ProductUsers','pu')
			->join('pu.product','p')
			->leftJoin('pu.promotion','pr')
			->join('pu.user','user')
			->where('user.id = :id')
			->orderBy($cr[0] , $cr[1])
			->setFirstResult($offset)
			->setMaxResults($limit)
		    ->setParameter('id', $this->getSuperAdminId());
		$query = $db->getQuery();
		return $query->getResult();
	}

	/**
	 * @return mixed
	 * @throws \Doctrine\ORM\NonUniqueResultException
	 */
	public function findAllUserProducts(){
		$em = $this->getEntityManager();
		$query = $em->createQuery('SELECT pu , p , pct ,u
										FROM ShopBundle:ProductUsers pu
										JOIN pu.product p
										JOIN p.category pct  
										JOIN pu.user u 
										WHERE (u.id != :id) AND (pu.hasSell != 0)'
		);
		$query ->setParameter('id',$this->getSuperAdminId());
		return $query->getResult();
	}

	/**
	 * @param $id
	 *
	 * @return mixed|null
	 * @throws \Doctrine\ORM\NonUniqueResultException
	 */
	public function findOneProduct($id){
		$query = $this->em->createQueryBuilder()
			->select('pu')
			->from('ShopBundle:ProductUsers','pu')
			->join('pu.product','p')
			->leftJoin('pu.promotion','pr')
			->join('p.category','pct')
			->where('pu.id = :id')
		    ->setParameter('id', $id)
			->getQuery();
		
		try{
			return $query->getSingleResult();
		}   catch ( NoResultException $e) {
			return null;
		}
	}
	

	/**
	 * @param $ids
	 *
	 * @return mixed
	 */
	public function updateProductInactivePromotion($ids){
		$query = $this->em->createQuery('UPDATE ShopBundle\Entity\ProductUsers pu
											  SET pu.promotion = NULL 
											  WHERE pu.promotion IN (:ids) '
		);
		$query ->setParameter('ids',$ids);
		return $query->getResult();
	}

	/**
	 * @param null $productIds
	 * @param $overwritePromotionIds
	 *
	 * @return mixed
	 */
	public function checkProductUsersIdsByCategoryAndBiggestPromotions($productIds = null,$overwritePromotionIds){
		 $query = $this->em->createQuery('SELECT pu.id 
											   FROM ShopBundle\Entity\ProductUsers pu
											   WHERE pu.product IN(:prIds) AND 
											   (pu.promotion IN(:overwPrIds) OR pu.promotion IS NULL)
											   '
		 )->setParameter('prIds',$productIds)
		 ->setParameter('overwPrIds',$overwritePromotionIds)
		 ;
		 return $query->getResult();
	}

	/**
	 * @param $overwritePromotionIds
	 *
	 * @return mixed
	 */
	public function checkProductUsersIdsAllCategoryAndBiggestPromotions($overwritePromotionIds){
		$query = $this->em->createQuery('SELECT pu.id 
											  FROM ShopBundle\Entity\ProductUsers pu
											  WHERE (pu.promotion IN(:overwPrIds) OR pu.promotion IS NULL)
											   '
		)->setParameter('overwPrIds',$overwritePromotionIds)
		;
		return $query->getResult();
	}

	/**
	 * @param $pId
	 * @param null $productUserIds
	 *
	 * @return mixed
	 */
	public function updateProductsSetPromotionCategory($pId,$productUserIds=null){
		$query = $this->em->createQuery('UPDATE ShopBundle\Entity\ProductUsers pu
											  SET pu.promotion = :pid
											  WHERE pu.id IN (:ids)
											  '
		)->setParameter('pid',$pId)
		 ->setParameter('ids',$productUserIds);
		return $query->getResult();
	}



	public function findProductByCategory($id,$criteria=null){
		$cr = array('p.dateCreated','ASC');
		if(null !== $criteria){
		  $cr = $criteria;
		}
		$db = $this->em->createQueryBuilder();
		$db
			->select('pu','p')
			->from('ShopBundle:ProductUsers','pu')
			->join('pu.product','p')
			->join('p.category','cat')
			->where('cat.slug = ?1')
			->orderBy($cr[0],$cr[1])
			->setParameter(1,$id);

		$query = $db->getQuery();
		return $query->getResult();
	}
}

