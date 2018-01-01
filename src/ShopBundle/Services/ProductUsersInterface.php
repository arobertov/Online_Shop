<?php
/**
 * Created by PhpStorm.
 * User: Angel
 * Date: 20.12.2017 г.
 * Time: 11:17
 */

namespace ShopBundle\Services;


interface ProductUsersInterface {

	public  function removeInactivePromotion($ids);

	public function addPromotionByCategory($promotionId = null);

    public function listAllCompanyProducts($criteria = null);

	public function listProductByCategory($id,$criteria = null);

	public function addCriteria(Array $arr);
}