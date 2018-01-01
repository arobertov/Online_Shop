<?php

namespace ShopBundle\Repository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use ShopBundle\Entity\ProductCategory;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

/**
 * ProductCategoryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProductCategoryRepository extends NestedTreeRepository
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
		parent::__construct( $em,new ClassMetadata(ProductCategory::class));
		$this->em = $em;
	}

	public function findCategoryTreeJoinProducts(){
		$query = $this->em
			->createQueryBuilder()
			->select(array('node','products'))
			->from('ShopBundle:ProductCategory', 'node')
			->leftJoin('node.products','products')
			->orderBy('node.root, node.lft', 'ASC')
			->getQuery()
		;
		return $query;
	}

	public function findAllCategoriesTree(){
		$query = $this->em->createQuery('SELECT c ,cc ,pc,cp   
											  FROM ShopBundle\Entity\ProductCategory c
											  JOIN c.children cc
											  LEFT JOIN cc.products pc
											  LEFT JOIN cc.parent cp
											 
											  ');
		return $query->getResult();
	}

	public function getSubcategoryIds($id){
		$query = $this->em->createQuery('SELECT c.id
											  FROM ShopBundle\Entity\ProductCategory c
											  WHERE c.parent = :id	'
		)->setParameter('id',$id)
		;
		return $query->getResult();
	}

}
