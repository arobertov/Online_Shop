<?php
/**
 * Created by PhpStorm.
 * User: AngelRobertov
 * Date: 13.12.2017 г.
 * Time: 19:30
 */

namespace ShopBundle\Services;


use Doctrine\ORM\EntityManagerInterface;

class OrderProductService
{
    /**
     * @var  EntityManagerInterface
     */
    private $em;

    public function __construct( EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function findUser($userId){
        return $this->em->getRepository('AppBundle:User')->findOneBy(['id'=>$userId]);
    }
}