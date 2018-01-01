<?php
/**
 * Created by PhpStorm.
 * User: Angel
 * Date: 30.12.2017 г.
 * Time: 14:26
 */

namespace ShopBundle\Services;


interface ProductCategoryInterface {
	public function getCategoryTreeJoinProduct();
}