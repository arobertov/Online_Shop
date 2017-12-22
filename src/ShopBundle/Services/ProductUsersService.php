<?php
/**
 * Created by PhpStorm.
 * User: Angel
 * Date: 20.12.2017 г.
 * Time: 11:06
 */

namespace ShopBundle\Services;


use AppBundle\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use ShopBundle\Entity\Product;
use ShopBundle\Entity\ProductCategory;
use ShopBundle\Entity\ProductUsers;
use ShopBundle\Entity\Promotion;
use ShopBundle\Repository\ProductUsersRepository;

class ProductUsersService implements ProductUsersInterface {

	/**
	 * @var EntityManagerInterface $em
	 */
	private $em;

	/**
	 * @var ProductUsersRepository
	 */
	private $productUsersRepository;

	/**
	 * ProductUsersService constructor.
	 *
	 * @param EntityManagerInterface $em
	 * @param ProductUsersRepository $repository
	 */
	public function __construct(EntityManagerInterface $em ,ProductUsersRepository $repository  ) {
		$this->em = $em;
		$this->productUsersRepository = $repository;
	}

	public function findProductsByProductCategory($id){
		return $this->productUsersRepository->findAllProductByCategory($id);
	}


	public function removeInactivePromotion($ids){
		 $this->productUsersRepository->updateProductInactivePromotion($ids);
	}

	public function addPromotionByCategory($promotionId = null){
		$em = $this->em;
		$productUsersRepository = $this->productUsersRepository;
		$promotion = $em->getRepository(Promotion::class)->findOneBy(['id'=>$promotionId,'isActive'=>1]);
		if(null ===$promotion){
			return 'No find promotion';
		}

		$overwritePromotionIds = $em->getRepository( Promotion::class )
		                            ->checkBiggerPromotionDiscount( ( $promotion->getDiscount() * 0.01 ) );

		if(null !== $promotion->getProductCategory()) {
			$catId[] = $promotion->getProductCategory()->getId();
			$categoriesIds = $em->getRepository(ProductCategory::class)->getSubcategoryIds($catId);
			foreach ($categoriesIds as $id){
				$catId[] = $id['id'];
			}
			/*----- get product ids in category parameter   ------------------------*/
			$productIds      = $em->getRepository(Product::class)->findBy( [ 'category' => $catId ] );

			/*----- get product users in product and promotion smallest and equal in current promotion ------- */
			$productUserIds  = $productUsersRepository
			                      ->checkProductUsersIdsByCategoryAndBiggestPromotions( $productIds, $overwritePromotionIds );

			$result = $productUsersRepository->updateProductsSetPromotionCategory( $promotionId,$productUserIds );
		} else  {
			$productUserIds  = $productUsersRepository->checkProductUsersIdsAllCategoryAndBiggestPromotions($overwritePromotionIds);
			$result = $productUsersRepository->updateProductsSetPromotionCategory( $promotionId,$productUserIds );
		}
		return $result;
	}

}