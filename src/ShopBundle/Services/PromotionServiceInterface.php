<?php
/**
 * Created by PhpStorm.
 * User: Angel
 * Date: 19.12.2017 г.
 * Time: 23:52
 */

namespace ShopBundle\Services;


interface PromotionServiceInterface {

	public function checkPromotionDate();

    public function findInactivePromotion();


}