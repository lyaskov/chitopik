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
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="ProductId")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_id", referencedColumnName="product_id")
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
     * @ORM\Column(name="price_opt", type="decimal", precision=15, scale=2)
     */
    private $priceOpt;

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

} 