<?php

namespace ShopBundle\Entity;

use AppBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ShopBundle\Entity\Order;
use ShopBundle\Entity\Promotion;

/**
 * productUsers
 *
 * @ORM\Table(name="product_users")
 * @ORM\Entity(repositoryClass="ShopBundle\Repository\ProductUsersRepository")
 */
class ProductUsers
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
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="decimal", precision=8, scale=2)
     */
    private $price;

	/**
	 * @var bool
	 *
	 * @ORM\Column(name="has_sell", type="boolean")
	 */
	private $hasSell;

    /**
     * @var Product $product
     *
     * @ORM\ManyToOne(targetEntity="ShopBundle\Entity\Product",inversedBy="productToUser",cascade={"persist"})
     * @ORM\JoinColumn(name="product_id",referencedColumnName="id", onDelete="CASCADE")
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User",inversedBy="productToUsers",cascade={"persist"}))
     * @ORM\JoinColumn(name="user_id",referencedColumnName="id")
     */
    private $user;

	/**
	 * @var
	 *
	 * @ORM\ManyToMany(targetEntity="ShopBundle\Entity\Order",mappedBy="productUsers")
	 */
    private $orders;

	/**
	 * @var Promotion $promotion
	 *
	 * @ORM\ManyToOne(targetEntity="ShopBundle\Entity\Promotion",inversedBy="products",cascade={"persist"})
	 * @ORM\JoinColumn(name="promotion_id",referencedColumnName="id",nullable=true)
	 */
	private $promotion;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->hasSell = false;
	}

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
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return ProductUsers
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set price
     *
     * @param string $price
     *
     * @return ProductUsers
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set product
     *
     * @param Product $product
     *
     * @return ProductUsers
     */
    public function setProduct(Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return Product
     */
    public function getProduct()
    {
        return $this->product;
    }

	/**
	 * @return bool
	 */
	public function isHasSell() {
		return $this->hasSell;
	}

	/**
	 * @param bool $hasSell
	 */
	public function setHasSell( $hasSell ) {
		$this->hasSell = $hasSell;
	}


    /**
     * Set user
     *
     * @param User $user
     *
     * @return ProductUsers
     */
    public function setUser(User $user = null)
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

    /**
     * Set promotion
     *
     * @param Promotion $promotion
     *
     * @return ProductUsers
     */
    public function setPromotion( Promotion $promotion = null)
    {
        $this->promotion = $promotion;

        return $this;
    }

    /**
     * Get promotion
     *
     * @return Promotion
     */
    public function getPromotion()
    {
        return $this->promotion;
    }

    /**
     * Add order
     *
     * @param \ShopBundle\Entity\Order $order
     *
     * @return ProductUsers
     */
    public function addOrder(\ShopBundle\Entity\Order $order)
    {
        $this->orders[] = $order;

        return $this;
    }

    /**
     * Remove order
     *
     * @param \ShopBundle\Entity\Order $order
     */
    public function removeOrder(\ShopBundle\Entity\Order $order)
    {
        $this->orders->removeElement($order);
    }

    /**
     * Get orders
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrders()
    {
        return $this->orders;
    }

    public function isOwner(User $user = null){
    	return $user == $this->user;
    }
}
