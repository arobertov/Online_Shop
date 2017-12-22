<?php

namespace ShopBundle\Command;

use ShopBundle\Services\ProductUsersInterface;
use ShopBundle\Services\PromotionServiceInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CheckPromotionDateCommand extends ContainerAwareCommand {

	/**
	 * @var PromotionServiceInterface $promotionService
	 */
	private $promotionService;

	/**
	 * @var ProductUsersInterface $productUsersService
	 */
	private $productUsersService;

	/**
	 * CheckPromotionDateCommand constructor.
	 *
	 * @param PromotionServiceInterface $promotionService
	 * @param ProductUsersInterface $productUsers
	 */
	public function __construct( PromotionServiceInterface $promotionService,
									       ProductUsersInterface $productUsers) {
		parent::__construct();
		$this->promotionService = $promotionService;
		$this->productUsersService = $productUsers;
	}


	/**
	 * {@inheritdoc}
	 */
	protected function configure() {
		$this
			->setName( 'shop:check_promotion_date' )
			->setDescription( 'Check promotion date,update it to actual status.Check product promotion ,remove  it 
			promotion if promotion inactive!' );
	}

	/**
	 * {@inheritdoc}
	 */
	protected function execute( InputInterface $input, OutputInterface $output ) {

		$this->promotionService->checkPromotionDate();
		$inactivePromotionIds = $this->promotionService->findInactivePromotion();
		$this->productUsersService->removeInactivePromotion($inactivePromotionIds);

	    $output->write('OK');
	}
}