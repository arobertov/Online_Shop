<?php

namespace ShopBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use ShopBundle\Entity\Product;
use ShopBundle\Entity\ProductCategory;

/**
 * Promotion
 *
 * @ORM\Table(name="promotion")
 * @ORM\Entity(repositoryClass="ShopBundle\Repository\PromotionRepository")
 */
class Promotion
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
     * @ORM\Column(name="title", type="string", length=255, unique=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="discount", type="decimal", precision=2, scale=2)
     */
    private $discount;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dueDate", type="datetime", nullable=true)
     */
    private $dueDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="endDate", type="datetime", nullable=true)
     */
    private $endDate;

	/**
	 * @var bool
	 *
	 * @ORM\Column(name="is_active", type="boolean")
	 */
    private $isActive;

    /**
     * @var Product $products
     *
     * @ORM\OneToMany(targetEntity="ShopBundle\Entity\ProductUsers",mappedBy="promotion")
     */
    private $products;

	/**
	 * @var ProductCategory
	 *
	 * @ORM\ManyToOne(targetEntity="ShopBundle\Entity\ProductCategory",inversedBy="promotions",cascade={"persist"})
	 * @ORM\JoinColumn(name="category_id",referencedColumnName="id", nullable=true)
	 */
    private $productCategory;


	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->products = new ArrayCollection();
		$this->isActive = true;
	}


    /**
     * Get id
     *
     * @return int|null
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
     * @return Promotion
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
     * @return Promotion
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
     * Set discount
     *
     * @param string $discount
     *
     * @return Promotion
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;

        return $this;
    }

    /**
     * Get discount
     *
     * @return string|null
     */
    public function getDiscount(): ?string {

	    return $this->discount;
    }

    /**
     * Set dueDate
     *
     * @param \DateTime $dueDate
     *
     * @return Promotion
     */
    public function setDueDate($dueDate)
    {
        $this->dueDate = $dueDate;

        return $this;
    }

    /**
     * Get dueDate
     *
     * @return \DateTime
     */
    public function getDueDate()
    {
        return $this->dueDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     *
     * @return Promotion
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

	/**
	 * @return bool
	 */
	public function isActive() {
		return $this->isActive;
	}

	/**
	 * @param bool $isActive
	 */
	public function setIsActive( $isActive ) {
		$this->isActive = $isActive;
	}



    /**
     * Add product
     *
     * @param ProductUsers $product
     *
     * @return Promotion
     */
    public function addProduct(ProductUsers $product)
    {
        $this->products[] = $product;

        return $this;
    }

    /**
     * Remove product
     *
     * @param ProductUsers $product
     */
    public function removeProduct(ProductUsers $product)
    {
        $this->products->removeElement($product);
    }

    /**
     * Get products
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set productCategory
     *
     * @param ProductCategory $productCategory
     *
     * @return Promotion
     */
    public function setProductCategory( ProductCategory $productCategory = null)
    {
        $this->productCategory = $productCategory;

        return $this;
    }

    /**
     * Get productCategory
     *
     * @return ProductCategory
     */
    public function getProductCategory()
    {
        return $this->productCategory;
    }
}
