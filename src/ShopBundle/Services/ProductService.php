<?php
/**
 * Created by PhpStorm.
 * User: Angel
 * Date: 22.12.2017 г.
 * Time: 10:43
 */

namespace ShopBundle\Services;


use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProductService implements ProductServiceInterface {


	private $targetDir;

	/**
	 * ProductService constructor.
	 *
	 * @param $targetDir
	 */
	public function __construct( $targetDir ) {
		$this->targetDir = $targetDir;
	}


	public function fileUploader( UploadedFile $file ) {
		$fileName = md5(uniqid()).'.'.$file->guessExtension();

		$file->move($this->getTargetDir(), $fileName);

		return $fileName;
	}

	/**
	 * @return mixed
	 */
	public function getTargetDir() {
		return $this->targetDir;
	}


}