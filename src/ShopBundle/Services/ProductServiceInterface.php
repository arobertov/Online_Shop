<?php
/**
 * Created by PhpStorm.
 * User: Angel
 * Date: 22.12.2017 г.
 * Time: 10:40
 */

namespace ShopBundle\Services;


use ShopBundle\Entity\ProductUsers;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface ProductServiceInterface {

	/**
	 * @param ProductUsers $productUsers
	 *
	 * @return mixed
	 */
	public function uploadedFile(ProductUsers $productUsers);


}