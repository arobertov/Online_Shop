<?php

namespace ShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="ShopBundle\Repository\ProductRepository")
 */
class Product
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
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="features", type="text", nullable=true)
     */
    private $features;

    /**
     * @var string
     *
     * @ORM\Column(name="information", type="text",nullable=true)
     */
    private $information;

    /**
     * @var int
     * @Assert\Range(
     *     min="1"
     * )
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity;

    /**
     * @var int
     *
     * @ORM\Column(name="rating", type="integer")
     */
    private $rating;

    /**
     * @var float
     *
     * @ORM\Column(name="discount", type="decimal",precision=2,scale=2)
     */
    private $discount;

    /**
     * @var float
     * @ORM\Column(name="price",type="decimal",precision=7,scale=2)
     */
    private $price;

    /**
     * @var \DateTime
     * @ORM\Column(name="date_created",type="datetime")
     */
    private $dateCreated;

	/**
	 * @var float
	 */
    private $subtotal;


    /**
     * @ORM\ManyToOne(targetEntity="ShopBundle\Entity\ProductCategory",inversedBy="products")
     * @ORM\JoinColumn(name="categoryId",referencedColumnName="id")
     */
    private $category;


    


    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
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
     * Set title
     *
     * @param string $title
     *
     * @return Product
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Product
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set features
     *
     * @param string $features
     *
     * @return Product
     */
    public function setFeatures($features)
    {
        $this->features = $features;

        return $this;
    }

    /**
     * Get features
     *
     * @return string
     */
    public function getFeatures()
    {
        return $this->features;
    }

    /**
     * Set information
     *
     * @param string $information
     *
     * @return Product
     */
    public function setInformation($information)
    {
        $this->information = $information;

        return $this;
    }

    /**
     * Get information
     *
     * @return string
     */
    public function getInformation()
    {
        return $this->information;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return Product
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
     * Set rating
     *
     * @param integer $rating
     *
     * @return Product
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get rating
     *
     * @return int
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Set discount
     *
     * @param boolean $discount
     *
     * @return Product
     */
    public function setDiscount($discount)
    {
        $this->discount = ($discount/100);

        return $this;
    }

    /**
     * Get discount
     *
     * @return bool
     */
    public function getDiscount()
    {
        return $this->discount*100;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return \DateTime
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

	/**
	 * @param \DateTime $dateCreated
	 *
	 * @return $this
	 */
    public function setDateCreated($dateCreated)
    {
	    $this->dateCreated = $dateCreated;

	    return $this;
    }

	/**
	 * @return float
	 */
	public function getSubtotal() {
		return $this->subtotal;
	}

	/**
	 * @param float $subtotal
	 */
	public function setSubtotal( $subtotal ) {
		$this->subtotal = $subtotal;
	}


}

