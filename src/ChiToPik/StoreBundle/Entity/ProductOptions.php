<?php

namespace ChiToPik\StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * ProductOptions
 *
 * @ORM\Table(name="product_options")
 * @ORM\Entity
 */
class ProductOptions {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \ProductId
     *
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="productOptions")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_id", referencedColumnName="product_id", onDelete="CASCADE")
     * })
     */
    private $productId;

    /**
     * @var Decimal
     *
     * @ORM\Column(name="price", type="decimal", precision=15, scale=2)
     */
    private $price;

    /**
     * @var Decimal
     *
     * @ORM\Column(name="price_max", type="decimal", precision=15, scale=2)
     */
    private $priceMax;

    /**
     * @var Date
     *
     * @ORM\Column(name="date_time_created", type="datetime", nullable=true)
     */
    private $dateTimeCreated;

    /**
     * @var Decimal
     *
     * @ORM\Column(name="star_rating", type="decimal", precision=15, scale=2)
     */
    private $starRating;

    /**
     * @var Integer
     *
     * @ORM\Column(name="count_feed_back", type="integer")
     */
    private $countFeedBack;

    /**
     * @var Integer
     *
     * @ORM\Column(name="count_orders", type="integer")
     */
    private $countOrders;

    /**
     * @var String
     *
     * @ORM\Column(name="price_unit", type="string", length=50)
     */
    private $priceUnit;

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
     * Set price
     *
     * @param float $price
     * @return ProductOptions
     */
    public function setPrice($price)
    {
        $this->price = $price;
    
        return $this;
    }

    /**
     * Get price
     *
     * @return float 
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set dateTimeCreated
     *
     * @param \DateTime $dateTimeCreated
     * @return ProductOptions
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
     * Set starRating
     *
     * @param float $starRating
     * @return ProductOptions
     */
    public function setStarRating($starRating)
    {
        $this->starRating = $starRating;
    
        return $this;
    }

    /**
     * Get starRating
     *
     * @return float 
     */
    public function getStarRating()
    {
        return $this->starRating;
    }

    /**
     * Set countFeedBack
     *
     * @param integer $countFeedBack
     * @return ProductOptions
     */
    public function setCountFeedBack($countFeedBack)
    {
        $this->countFeedBack = $countFeedBack;
    
        return $this;
    }

    /**
     * Get countFeedBack
     *
     * @return integer 
     */
    public function getCountFeedBack()
    {
        return $this->countFeedBack;
    }

    /**
     * Set countOrders
     *
     * @param integer $countOrders
     * @return ProductOptions
     */
    public function setCountOrders($countOrders)
    {
        $this->countOrders = $countOrders;
    
        return $this;
    }

    /**
     * Get countOrders
     *
     * @return integer 
     */
    public function getCountOrders()
    {
        return $this->countOrders;
    }

    /**
     * Set productId
     *
     * @param \ChiToPik\StoreBundle\Entity\Product $productId
     * @return ProductOptions
     */
    public function setProductId(\ChiToPik\StoreBundle\Entity\Product $productId = null)
    {
        $this->productId = $productId;
    
        return $this;
    }

    /**
     * Get productId
     *
     * @return \ChiToPik\StoreBundle\Entity\Product 
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * Set priceMax
     *
     * @param float $priceMax
     * @return ProductOptions
     */
    public function setPriceMax($priceMax)
    {
        $this->priceMax = $priceMax;
    
        return $this;
    }

    /**
     * Get priceMax
     *
     * @return float 
     */
    public function getPriceMax()
    {
        return $this->priceMax;
    }

    /**
     * Set priceUnit
     *
     * @param string $priceUnit
     * @return ProductOptions
     */
    public function setPriceUnit($priceUnit)
    {
        $this->priceUnit = $priceUnit;
    
        return $this;
    }

    /**
     * Get priceUnit
     *
     * @return string 
     */
    public function getPriceUnit()
    {
        return $this->priceUnit;
    }
}