<?php

namespace ChiToPik\StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * Category_Store
 *
 * @ORM\Entity
 * @ORM\Table(
 *            name="category_store",
 *            indexes={@ORM\Index(name="parent_id_index1", columns={"parent_id"})
 *             })
 */
class CategoryStore
{

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Integer
     *
     * @ORM\Column(name="parent_id", type="integer")
     */
    private $parentId;

    /**
     * @var Integer
     *
     * @ORM\Column(name="count_products", type="integer")
     */
    private $countProducts;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="category_url", type="string", length=255, nullable=false)
     */
    private $categoryUrl;

    /**
     * @var \Store
     *
     * @ORM\ManyToOne(targetEntity="Store", inversedBy="storeId")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="store_id", referencedColumnName="store_id")
     * })
     */
    private $storeId;

    /**
     * @var integer
     *
     * @ORM\Column(name="category_store_id", type="integer", unique=true)
     */
    private $categoryStoreId;

    /**
     * @ORM\OneToMany(targetEntity="Product", mappedBy="categoryStore")
     */
    private $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set parentId
     *
     * @param integer $parentId
     * @return CategoryStore
     */
    public function setParentId($parentId)
    {
        $this->parentId = $parentId;
    
        return $this;
    }

    /**
     * Get parentId
     *
     * @return integer 
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * Set countProducts
     *
     * @param integer $countProducts
     * @return CategoryStore
     */
    public function setCountProducts($countProducts)
    {
        $this->countProducts = $countProducts;
    
        return $this;
    }

    /**
     * Get countProducts
     *
     * @return integer 
     */
    public function getCountProducts()
    {
        return $this->countProducts;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return CategoryStore
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
     * Set categoryUrl
     *
     * @param string $categoryUrl
     * @return CategoryStore
     */
    public function setCategoryUrl($categoryUrl)
    {
        $this->categoryUrl = $categoryUrl;
    
        return $this;
    }

    /**
     * Get categoryUrl
     *
     * @return string 
     */
    public function getCategoryUrl()
    {
        return $this->categoryUrl;
    }

    /**
     * Set categoryStoreId
     *
     * @param integer $categoryStoreId
     * @return CategoryStore
     */
    public function setCategoryStoreId($categoryStoreId)
    {
        $this->categoryStoreId = $categoryStoreId;
    
        return $this;
    }

    /**
     * Get categoryStoreId
     *
     * @return integer 
     */
    public function getCategoryStoreId()
    {
        return $this->categoryStoreId;
    }

    /**
     * Set storeId
     *
     * @param \ChiToPik\StoreBundle\Entity\Store $storeId
     * @return CategoryStore
     */
    public function setStoreId(\ChiToPik\StoreBundle\Entity\Store $storeId = null)
    {
        $this->storeId = $storeId;
    
        return $this;
    }

    /**
     * Get storeId
     *
     * @return \ChiToPik\StoreBundle\Entity\Store 
     */
    public function getStoreId()
    {
        return $this->storeId;
    }

    /**
     * Add products
     *
     * @param \ChiToPik\StoreBundle\Entity\Product $products
     * @return CategoryStore
     */
    public function addProduct(\ChiToPik\StoreBundle\Entity\Product $products)
    {
        $this->products[] = $products;
    
        return $this;
    }

    /**
     * Remove products
     *
     * @param \ChiToPik\StoreBundle\Entity\Product $products
     */
    public function removeProduct(\ChiToPik\StoreBundle\Entity\Product $products)
    {
        $this->products->removeElement($products);
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
}