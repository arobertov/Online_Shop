<?php

namespace ShopBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * ProductCategory
 * @Gedmo\Tree(type="nested")
 * @ORM\Table(name="product_categories")
 * @ORM\Entity(repositoryClass="ShopBundle\Repository\ProductCategoryRepository")
 */
class ProductCategory
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
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

	/**
	 * @var string
	 *
	 * @Gedmo\Slug(fields={"name"})
	 * @ORM\Column(name="slug", type="string", length=255, unique=true)
	 */
    private $slug;

	/**
	 * @var int
	 * @Gedmo\TreeLeft
	 * @ORM\Column(name="lft", type="integer")
	 */
	private $lft;

	/**
	 * @var int
	 * @Gedmo\TreeLevel
	 * @ORM\Column(name="lvl", type="integer")
	 */
	private $lvl;

	/**
	 * @var int
	 * @Gedmo\TreeRight
	 * @ORM\Column(name="rgt", type="integer")
	 */
	private $rgt;


	/**
	 * @Gedmo\TreeRoot
	 * @ORM\ManyToOne(targetEntity="ShopBundle\Entity\ProductCategory")
	 * @ORM\JoinColumn(name="tree_root", referencedColumnName="id", onDelete="CASCADE")
	 */
	private $root;

	/**
	 * @Gedmo\TreeParent
	 * @ORM\ManyToOne(targetEntity="ShopBundle\Entity\ProductCategory", inversedBy="children")
	 * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	private $parent;

	/**
	 * @ORM\OneToMany(targetEntity="ShopBundle\Entity\ProductCategory", mappedBy="parent")
	 * @ORM\OrderBy({"lft" = "ASC"})
	 */
	private $children;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="ShopBundle\Entity\Product",mappedBy="category")
     */
    private $products;

	/**
	 * @var ArrayCollection
	 * @ORM\OneToMany(targetEntity="ShopBundle\Entity\Promotion",mappedBy="productCategory")
	 */
    private $promotions;

    /**
     * @return ArrayCollection
     */
    public function getProducts()
    {
        return $this->products;
    }


    /**
     * ProductCategory constructor.
     */
    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->children = new ArrayCollection();
        $this->promotions = new ArrayCollection();
    }

    public function __toString() {
	    return $this->name;
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
     * Set name
     *
     * @param string $name
     *
     * @return ProductCategory
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

	/**
	 * @return string
	 */
	public function getSlug(): string {
		return $this->slug;
	}

	/**
	 * @param string $slug
	 */
	public function setSlug( string $slug ): void {
		$this->slug = $slug;
	}



	/**
	 * @return int
	 */
	public function getLft(): int {
		return $this->lft;
	}

	/**
	 * @param int $lft
	 */
	public function setLft( int $lft ): void {
		$this->lft = $lft;
	}

	/**
	 * @return int
	 */
	public function getLvl(): int {
		return $this->lvl;
	}

	/**
	 * @param int $lvl
	 */
	public function setLvl( int $lvl ): void {
		$this->lvl = $lvl;
	}

	/**
	 * @return int
	 */
	public function getRgt(): int {
		return $this->rgt;
	}

	/**
	 * @param int $rgt
	 */
	public function setRgt( int $rgt ): void {
		$this->rgt = $rgt;
	}

	/**
	 * @return mixed
	 */
	public function getRoot() {
		return $this->root;
	}

	/**
	 * @param mixed $root
	 */
	public function setRoot( $root ): void {
		$this->root = $root;
	}

    /**
     * Add child
     *
     * @param \ShopBundle\Entity\ProductCategory $child
     *
     * @return ProductCategory
     */
    public function addChild(ProductCategory $child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Remove child
     *
     * @param \ShopBundle\Entity\ProductCategory $child
     */
    public function removeChild(ProductCategory $child)
    {
        $this->children->removeElement($child);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set parent
     *
     * @param \ShopBundle\Entity\ProductCategory $parent
     *
     * @return ProductCategory
     */
    public function setParent(ProductCategory $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \ShopBundle\Entity\ProductCategory
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add product
     *
     * @param Product $product
     *
     * @return ProductCategory
     */
    public function addProduct(Product $product)
    {
        $this->products[] = $product;

        return $this;
    }

    /**
     * Remove product
     *
     * @param Product $product
     */
    public function removeProduct(Product $product)
    {
        $this->products->removeElement($product);
    }

    /**
     * Add promotion
     *
     * @param Promotion $promotion
     *
     * @return ProductCategory
     */
    public function addPromotion( Promotion $promotion)
    {
        $this->promotions[] = $promotion;

        return $this;
    }

    /**
     * Remove promotion
     *
     * @param Promotion $promotion
     */
    public function removePromotion( Promotion $promotion)
    {
        $this->promotions->removeElement($promotion);
    }

    /**
     * Get promotions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPromotions()
    {
        return $this->promotions;
    }
}
