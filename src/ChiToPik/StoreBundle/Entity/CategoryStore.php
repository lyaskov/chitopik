<?php

namespace ChiToPik\StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Category_Store
 *
 * @ORM\Table(name="category_store")
 * @ORM\Entity
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

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
} 