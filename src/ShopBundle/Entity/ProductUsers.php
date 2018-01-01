<?php

namespace ShopBundle\Entity;

use AppBundle\Entity\User;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ShopBundle\Entity\Order;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @Assert\NotBlank()
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
	 * @var float
	 */
    private $priceDiscount;

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
	public function __construct( )
	{
		$this->hasSell = false;
	}

    /**
     * Get id
     *
     * @return int
     */
    public function getId():?int
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
    public function setQuantity($quantity):?ProductUsers
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return int
     */
    public function getQuantity():?int
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
    public function setPrice($price):?ProductUsers
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice():?string
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
    public function setProduct(Product $product = null):?ProductUsers
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return Product
     */
    public function getProduct():?Product
    {
        return $this->product;
    }

	/**
	 * @return float
	 */
	public function getPriceDiscount():?float
	{
		$price = $this->getPrice();
		$discount = $this->getPromotion()->getDiscount();
		return ($price - ($price * $discount));
	}

	
	/**
	 * @return bool
	 */
	public function isHasSell():?bool {
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
     *
     */
    public function setPromotion( Promotion $promotion = null): void
    {
        $this->promotion = $promotion;

    }

    /**
     * Get promotion
     *
     * @return Promotion
     */
    public function getPromotion():?Promotion
    {
        return $this->promotion;
    }

    /**
     * Add order
     *
     * @param Order $order
     *
     * @return ProductUsers
     */
    public function addOrder( Order $order):?ProductUsers
    {
        $this->orders[] = $order;

        return $this;
    }

    /**
     * Remove order
     *
     * @param Order $order
     */
    public function removeOrder( Order $order):void
    {
        $this->orders->removeElement($order);
    }

    /**
     * Get orders
     *
     * @return Collection
     */
    public function getOrders():?Collection
    {
        return $this->orders;
    }

	/**
	 * @param User|null $user
	 *
	 * @return bool
	 */
    public function isOwner(User $user = null):bool
    {
    	return $user == $this->user;
    }
}
