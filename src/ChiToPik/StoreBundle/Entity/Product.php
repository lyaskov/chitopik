<?php

namespace ChiToPik\StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

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
     * @var Date
     *
     * @ORM\Column(name="date_time_created", type="datetime")
     */
    private $dateTimeCreated;

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
     * @var \Category_Sore_Id
     *
     * @ORM\ManyToOne(targetEntity="CategoryStore", inversedBy="categoryStoreId")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="category_store_id", referencedColumnName="category_store_id")
     * })
     */
    private $categoryStoreId;

    /**
     * @var \Shipping_Id
     *
     * @ORM\ManyToOne(targetEntity="Shipping", inversedBy="shippingId")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="shipping_id", referencedColumnName="shipping_id")
     * })
     */
    private $shippingId;

    /**
     * @ORM\OneToMany(targetEntity="ProductOptions", mappedBy="product")
     */
    private $productOptions;

    public function __construct()
    {
        $this->productOptions = new ArrayCollection();
    }



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

    /**
     * Set dateTimeCreated
     *
     * @param \DateTime $dateTimeCreated
     * @return Product
     */
    public function setDateTimeCreated($dateTimeCreated)
    {
        $this->dateTimeCreated = $dateTimeCreated;
    
        return $this;
    }

    /**
     * Get dateTimeCreated
     *
     * @return \DateTime 
     */
    public function getDateTimeCreated()
    {
        return $this->dateTimeCreated;
    }

    /**
     * Set categoryStoreId
     *
     * @param \ChiToPik\StoreBundle\Entity\CategoryStore $categoryStoreId
     * @return Product
     */
    public function setCategoryStoreId(\ChiToPik\StoreBundle\Entity\CategoryStore $categoryStoreId = null)
    {
        $this->categoryStoreId = $categoryStoreId;
    
        return $this;
    }

    /**
     * Get categoryStoreId
     *
     * @return \ChiToPik\StoreBundle\Entity\CategoryStore 
     */
    public function getCategoryStoreId()
    {
        return $this->categoryStoreId;
    }

    /**
     * Set shippingId
     *
     * @param \ChiToPik\StoreBundle\Entity\Shipping $shippingId
     * @return Product
     */
    public function setShippingId(\ChiToPik\StoreBundle\Entity\Shipping $shippingId = null)
    {
        $this->shippingId = $shippingId;
    
        return $this;
    }

    /**
     * Get shippingId
     *
     * @return \ChiToPik\StoreBundle\Entity\Shipping 
     */
    public function getShippingId()
    {
        return $this->shippingId;
    }

    /**
     * Add productOptions
     *
     * @param \ChiToPik\StoreBundle\Entity\ProductOptions $productOptions
     * @return Product
     */
    public function addProductOption(\ChiToPik\StoreBundle\Entity\ProductOptions $productOptions)
    {
        $this->productOptions[] = $productOptions;
    
        return $this;
    }

    /**
     * Remove productOptions
     *
     * @param \ChiToPik\StoreBundle\Entity\ProductOptions $productOptions
     */
    public function removeProductOption(\ChiToPik\StoreBundle\Entity\ProductOptions $productOptions)
    {
        $this->productOptions->removeElement($productOptions);
    }

    /**
     * Get productOptions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProductOptions()
    {
        return $this->productOptions;
    }
}