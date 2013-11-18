<?php

namespace ChiToPik\StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity
 */
class Product
{
    /**
     * @var integer
     *
     * @ORM\Column(name="product_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $productId;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="product_url", type="string", length=255, nullable=true)
     */
    private $productUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="photo", type="string", length=255, nullable=false)
     */
    private $photo;

    /**
     * @var \Category
     *
     * @ORM\ManyToOne(targetEntity="Category")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="category_id", referencedColumnName="category_id")
     * })
     */
    private $categoryId;

    /**
     * @var \Store
     *
     * @ORM\ManyToOne(targetEntity="Store")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="store_id", referencedColumnName="store_id")
     * })
     */
    private $storeId;



    /**
     * Get productId
     *
     * @return integer 
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Product
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
     * Set description
     *
     * @param string $description
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
     * Set productUrl
     *
     * @param string $productUrl
     * @return Product
     */
    public function setProductUrl($productUrl)
    {
        $this->productUrl = $productUrl;
    
        return $this;
    }

    /**
     * Get productUrl
     *
     * @return string 
     */
    public function getProductUrl()
    {
        return $this->productUrl;
    }

    /**
     * Set photo
     *
     * @param string $photo
     * @return Product
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;
    
        return $this;
    }

    /**
     * Get photo
     *
     * @return string 
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Set categoryId
     *
     * @param \ChiToPik\StoreBundle\Entity\Category $categoryId
     * @return Product
     */
    public function setCategoryId(\ChiToPik\StoreBundle\Entity\Category $categoryId = null)
    {
        $this->categoryId = $categoryId;
    
        return $this;
    }

    /**
     * Get categoryId
     *
     * @return \ChiToPik\StoreBundle\Entity\Category 
     */
    public function getCategoryId()
    {
        return $this->categoryId;
    }

    /**
     * Set storeId
     *
     * @param \ChiToPik\StoreBundle\Entity\Store $storeId
     * @return Product
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
}