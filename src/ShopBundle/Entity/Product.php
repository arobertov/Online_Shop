<?php

namespace ShopBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use ShopBundle\Entity\ProductUsers;
use ShopBundle\Entity\Promotion;
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
     *
     * @ORM\Column(name="rating", type="integer")
     */
    private $rating;


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
     * @ORM\ManyToOne(targetEntity="ShopBundle\Entity\ProductCategory",inversedBy="products",cascade={"persist"})
     * @ORM\JoinColumn(name="categoryId",referencedColumnName="id")
     */
    private $category;



    /**
     * @var ProductUsers $productToUser
     *
     * @ORM\OneToMany(targetEntity="ShopBundle\Entity\ProductUsers",mappedBy="product")
     */
    private $productToUser;


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


    /**
     * Set productToUser
     *
     * @param ProductUsers $productToUser
     *
     * @return Product
     */
    public function setProductToUser(ProductUsers $productToUser = null)
    {
        $this->productToUser = $productToUser;

        return $this;
    }

    /**
     * Get productToUser
     *
     * @return ArrayCollection
     */
    public function getProductToUser()
    {
        return $this->productToUser;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->productToUser = new ArrayCollection();
    }

    /**
     * Add productToUser
     *
     * @param ProductUsers $productToUser
     *
     * @return Product
     */
    public function addProductToUser(ProductUsers $productToUser)
    {
        $this->productToUser[] = $productToUser;

        return $this;
    }

    /**
     * Remove productToUser
     *
     * @param \ShopBundle\Entity\ProductUsers $productToUser
     */
    public function removeProductToUser(\ShopBundle\Entity\ProductUsers $productToUser)
    {
        $this->productToUser->removeElement($productToUser);
    }

    public function __toString() {
	    return $this->getTitle();
    }
}
