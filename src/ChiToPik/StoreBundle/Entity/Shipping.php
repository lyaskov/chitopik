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



} 