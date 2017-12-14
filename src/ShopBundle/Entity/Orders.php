<?php

namespace ShopBundle\Entity;

use AppBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * Orders
 *
 * @ORM\Table(name="orders")
 * @ORM\Entity(repositoryClass="ShopBundle\Repository\OrdersRepository")
 */
class Orders
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="totalAmount", type="decimal",precision=10,scale=2)
     */
    private $totalAmount;

    /**
     * @var string
     *
     * @ORM\Column(name="shipCity", type="string", length=255)
     */
    private $shipCity;

    /**
     * @var string
     *
     * @ORM\Column(name="shipAddress", type="string", length=255)
     */
    private $shipAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="orderPhone", type="string", length=255)
     */
    private $orderPhone;

    /**
     * @var string
     *
     * @ORM\Column(name="orderEmail", type="string", length=255)
     */
    private $orderEmail;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="orderDate", type="datetime")
     */
    private $orderDate;

    /**
     * @var User $user
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User",inversedBy="orders")
     * @ORM\JoinColumn(name="user_id",referencedColumnName="id")
     */
    private $user;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set totalAmount
     *
     * @param integer $totalAmount
     *
     * @return Orders
     */
    public function setTotalAmount($totalAmount)
    {
        $this->totalAmount = $totalAmount;

        return $this;
    }

    /**
     * Get totalAmount
     *
     * @return int
     */
    public function getTotalAmount()
    {
        return $this->totalAmount;
    }

    /**
     * Set shipCity
     *
     * @param string $shipCity
     *
     * @return Orders
     */
    public function setShipCity($shipCity)
    {
        $this->shipCity = $shipCity;

        return $this;
    }

    /**
     * Get shipCity
     *
     * @return string
     */
    public function getShipCity()
    {
        return $this->shipCity;
    }

    /**
     * Set shipAddress
     *
     * @param string $shipAddress
     *
     * @return Orders
     */
    public function setShipAddress($shipAddress)
    {
        $this->shipAddress = $shipAddress;

        return $this;
    }

    /**
     * Get shipAddress
     *
     * @return string
     */
    public function getShipAddress()
    {
        return $this->shipAddress;
    }

    /**
     * Set orderPhone
     *
     * @param string $orderPhone
     *
     * @return Orders
     */
    public function setOrderPhone($orderPhone)
    {
        $this->orderPhone = $orderPhone;

        return $this;
    }

    /**
     * Get orderPhone
     *
     * @return string
     */
    public function getOrderPhone()
    {
        return $this->orderPhone;
    }

    /**
     * Set orderEmail
     *
     * @param string $orderEmail
     *
     * @return Orders
     */
    public function setOrderEmail($orderEmail)
    {
        $this->orderEmail = $orderEmail;

        return $this;
    }

    /**
     * Get orderEmail
     *
     * @return string
     */
    public function getOrderEmail()
    {
        return $this->orderEmail;
    }

    /**
     * Set orderDate
     *
     * @param \DateTime $orderDate
     *
     * @return Orders
     */
    public function setOrderDate($orderDate)
    {
        $this->orderDate = $orderDate;

        return $this;
    }

    /**
     * Get orderDate
     *
     * @return \DateTime
     */
    public function getOrderDate()
    {
        return $this->orderDate;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Orders
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
