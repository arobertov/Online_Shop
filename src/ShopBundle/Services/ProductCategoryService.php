<?php
/**
 * Created by PhpStorm.
 * User: Angel
 * Date: 30.12.2017 г.
 * Time: 14:27
 */

namespace ShopBundle\Services;


use ShopBundle\Repository\ProductCategoryRepository;

class ProductCategoryService implements ProductCategoryInterface {

	/**
	 * @var ProductCategoryRepository
	 */
	private $treeRepo;

	/**
	 * ProductCategoryService constructor.
	 *
	 * @param ProductCategoryRepository $treeRepo
	 */
	public function __construct( ProductCategoryRepository $treeRepo ) {
		$this->treeRepo = $treeRepo;
	}


	public function getCategoryTreeJoinProduct() {
		$query = $this->treeRepo->findCategoryTreeJoinProducts();
		$options = array(
			'decorate' => true,
			'rootOpen' => function($tree) {
				if(count($tree) && ($tree[0]['lvl'] == 1)){
					return '<ul>';
				}
				},
			'rootClose' => '</ul></li>',
			'childOpen' => '',
			'childClose' => '',
			'nodeDecorator' => function($node) {
				if($node['lvl'] == 0) {
					return '<li class="subMenu open"><a>' . $node['name'] . '</a>';
				} else return '<li><a  href="/'.$node['id'].'/category"><i class="icon-chevron-right"></i>'
				              .$node['name'].' ('.count($node['products']).')</a></li>';
			} )
		;
		return  $this->treeRepo->buildTree($query->getArrayResult(), $options);
	}
}