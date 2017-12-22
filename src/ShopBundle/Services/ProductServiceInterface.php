<?php
/**
 * Created by PhpStorm.
 * User: Angel
 * Date: 22.12.2017 г.
 * Time: 10:40
 */

namespace ShopBundle\Services;


use Symfony\Component\HttpFoundation\File\UploadedFile;

interface ProductServiceInterface {
	  public function fileUploader(UploadedFile $file);
}