<?php
/**
 * Created by PhpStorm.
 * User: AngelRobertov
 * Date: 14.12.2017 г.
 * Time: 14:29
 */

namespace ShopBundle\Form\DataTransform;


use Doctrine\ORM\EntityManagerInterface;

class PriceToNumberTransformer
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }


}