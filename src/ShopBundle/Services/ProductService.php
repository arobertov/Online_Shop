<?php
/**
 * Created by PhpStorm.
 * User: Angel
 * Date: 22.12.2017 г.
 * Time: 10:43
 */

namespace ShopBundle\Services;


use ShopBundle\Entity\ProductUsers;
use ShopBundle\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProductService implements ProductServiceInterface {

	/**
	 * @var
	 */
	private $targetDir;

	/**
	 * @var ProductRepository $productRepository
	 */
	private $productRepository;

	/**
	 * ProductService constructor.
	 *
	 * @param $targetDir
	 * @param ProductRepository $product_repository
	 */
	public function __construct( $targetDir ,ProductRepository $product_repository) {
		$this->targetDir = $targetDir;
		$this->productRepository = $product_repository;
	}



	/**
	 * @param ProductUsers $productUsers
	 *
	 * @return ProductUsers
	 */
	public function uploadedFile(ProductUsers $productUsers){
		/** @var UploadedFile $file */
		$file = $productUsers->getProduct()->getImage();
		$fileName = $this->fileUploader($file);
		$productUsers->getProduct()->setImage($fileName);
		return $productUsers;
	}

	/**
	 * @param UploadedFile $file
	 *
	 * @return string
	 */
	public function fileUploader(UploadedFile $file ) {
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