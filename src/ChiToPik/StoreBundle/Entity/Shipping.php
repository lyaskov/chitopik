<?php

namespace ChiToPik\StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Shipping
 *
 * @ORM\Table(name="shipping")
 * @ORM\Entity
 */
class Shipping {

    /**
     * @var integer
     *
     * @ORM\Column(name="shipping_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $shippingId;


    /**
     * @var string
     *
     * @ORM\Column(name="type_name", type="string", length=255, nullable=false)
     */
    private $typeName;


    /**
     * @ORM\OneToMany(targetEntity="Product", mappedBy="categoryStore")
     */
    private $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }




    /**
     * Get shippingId
     *
     * @return integer 
     */
    public function getShippingId()
    {
        return $this->shippingId;
    }

    /**
     * Set typeName
     *
     * @param string $typeName
     * @return Shipping
     */
    public function setTypeName($typeName)
    {
        $this->typeName = $typeName;
    
        return $this;
    }

    /**
     * Get typeName
     *
     * @return string 
     */
    public function getTypeName()
    {
        return $this->typeName;
    }

    /**
     * Add products
     *
     * @param \ChiToPik\StoreBundle\Entity\Product $products
     * @return Shipping
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