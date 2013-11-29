<?php

namespace ChiToPik\ParserBundle\Command;

use ChiToPik\StoreBundle\Entity\Product;
use ChiToPik\StoreBundle\Entity\ProductOptions;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Sunra\PhpSimple\HtmlDomParser;
use ChiToPik\StoreBundle\Entity\CategoryStore;
use ChiToPik\StoreBundle\Entity\Store;


class ParseAliProductCommand extends ContainerAwareCommand
{
    private $categoryStoreId;

    private $categoryId;

    private $maxPrice;
    private $minPrice;
    private $parameters;

    /**
     * @return mixed
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    public function clearParameters(){
        $this->parameters = array();
    }

    /**
     * @param mixed $maxPrice
     */
    public function setMaxPrice($maxPrice)
    {
        $this->maxPrice = $maxPrice;
    }

    /**
     * @return mixed
     */
    public function getMaxPrice()
    {
        return $this->maxPrice;
    }

    /**
     * @param mixed $minPrice
     */
    public function setMinPrice($minPrice)
    {
        $this->minPrice = $minPrice;
    }
    /**
     * @return mixed
     */
    public function getMinPrice()
    {
        return $this->minPrice;
    }

    /**
     * @param mixed $categoryId
     */
    public function setCategoryId($categoryId)
    {
        $this->categoryId = $categoryId;
    }

    /**
     * @return mixed
     */
    public function getCategoryId()
    {
        return $this->categoryId;
    }

    /**
     * @param mixed $categoryStoreId
     */
    public function setCategoryStoreId($categoryStoreId)
    {
        $this->categoryStoreId = $categoryStoreId;
    }

    /**
     * @return mixed
     */
    public function getCategoryStoreId()
    {
        return $this->categoryStoreId;
    }

    public function configure()
    {
        $this
            ->setName('parser:ali:product')
            ->setDescription("Parser products AliExpres")
            ->addArgument('category', InputArgument::OPTIONAL, 'Category number')
            ->addArgument('min-price', InputArgument::OPTIONAL, 'Product with min price')
            ->addArgument('max-price', InputArgument::OPTIONAL, 'Product with man price');
            //->addOption('all',null,InputOption::VALUE_NONE, 'Parse all category from site AliExpress');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->setCategoryId(13);


        $category = $input->getArgument('category');
        $this->setCategoryStoreId($category);
        if ($input->getArgument('min-price')) {
            $this->setParameter('minPrice', $input->getArgument('min-price'));
            $this->setMinPrice($input->getArgument('min-price'));
        }
        if ($input->getArgument('max-price')) {
            $this->setParameter('maxPrice', $input->getArgument('max-price'));
            $this->setMaxPrice($input->getArgument('max-price'));
        }
        $this->setParameter('shipCountry', 'ru');

        $parametersStoreCategory = $this->getCategoryPageParameters(
            $this->getCategoryStoreId(),
            $this->getMinPrice(),
            $this->getMaxPrice()
        );
        $this->minPrice = $parametersStoreCategory['minPrice'];

        echo "Products in category: " . $parametersStoreCategory['countProducts'] . PHP_EOL;
        if ($parametersStoreCategory['countProducts'] < 9200) {
            $maxPage = $parametersStoreCategory['pageMax'];
            $this->goParseProducts($this->getCategoryStoreId(), $this->getCategoryId(), $maxPage);
        } else {
            echo "Sectors parser".PHP_EOL;
            $lastSector=false;
            while (!$lastSector) {
                $parametersStoreCategory = $this->getCategoryPageParameters(
                    $this->getCategoryStoreId(),
                    $this->getMinPrice()
                );
                if($parametersStoreCategory['pageMax']<262){
                    $lastSector = true;
                    $maxPage = $parametersStoreCategory['pageMax'];
                    echo "Parse products with price from " . $this->minPrice.PHP_EOL;
                    $this->goParseProducts($this->getCategoryStoreId(), $this->getCategoryId(), $maxPage);
                } else {
                    $tempMaxPrice = $this->minPrice*2;
                    $parametersStoreCategory = $this->getCategoryPageParameters(
                        $this->getCategoryStoreId(),
                        $this->getMinPrice(),
                        $tempMaxPrice
                    );
                    $maxPage = $parametersStoreCategory['pageMax'];
                    while($maxPage<262){
                        $tempMaxPrice += $tempMaxPrice/2;
                        $parametersStoreCategory = $this->getCategoryPageParameters(
                            $this->getCategoryStoreId(),
                            $this->getMinPrice(),
                            $tempMaxPrice
                        );
                        $maxPage = $parametersStoreCategory['pageMax'];
                    }
                    $maxPrice = round(($this->minPrice+$tempMaxPrice)/2, 2);
                    $parametersStoreCategory = $this->getCategoryPageParameters(
                        $this->getCategoryStoreId(),
                        $this->getMinPrice(),
                        $maxPrice
                    );
                    while ($parametersStoreCategory['pageMax']==262){
                        $maxPrice = round(($this->minPrice+$maxPrice)/2, 2);
                        $parametersStoreCategory = $this->getCategoryPageParameters(
                            $this->getCategoryStoreId(),
                            $this->getMinPrice(),
                            $maxPrice
                        );
                    }
                    $maxPage = $parametersStoreCategory['pageMax'];
                    echo "Parse products with price from " . $this->minPrice. " to ".$maxPrice.PHP_EOL;
                    $this->setParameter('minPrice', $this->minPrice);
                    $this->setParameter('maxPrice', $maxPrice);
                    $this->goParseProducts($this->getCategoryStoreId(), $this->getCategoryId(), $maxPage);
                    $this->minPrice = $maxPrice;
                }

            }
        }
    }

    /**
     * Парсим и сохраняем товары
     *
     * @param int|string $storeCategory - категория в магазине
     * @param $category - Категория для сохранения
     * @param $maxPage - максимальная страница для парсинга
     */
    public function goParseProducts($storeCategory, $category, $maxPage){
        for ($i = 1; $i <= $maxPage; $i++) {
            echo "Page ".$i. " from " . $maxPage. PHP_EOL;
//            return true;
            $url = $this->urlGenerate($storeCategory, $i, $this->getParameters());
            $dataProducts = $this->parseProduct($url);
            $this->saveProducts($dataProducts);
        }
    }

    public function getCategoryPageParameters($categoryStoreId, $minPrice=null, $maxPrice=null)
    {
        $url = "http://www.aliexpress.com/premium/category/{$categoryStoreId}.html?&SortType=price_asc";

        if($this->getParameter('shipCountry')) {
            $url .= '&shipCountry=' . $this->getParameter('shipCountry');
        } else {
            $url .= '&shipCountry=ru';
        }

        if($maxPrice){
            $url .= '&maxPrice=' . $maxPrice;
        }
        if($minPrice){
            $url .= '&minPrice=' . $minPrice;
        }
        $result['url'] = $url;

        $content = file_get_contents($url);
        $dom = HtmlDomParser::str_get_html($content);

        $countProducts = $dom->find('.search-count');
        if ($countProducts) {
            $result['countProducts'] = (int)str_replace(array(',', ' '), '', $countProducts[0]->innertext);
        } else {
            $result['countProducts'] = null;
        }

        $prices = $dom->find('.info .price .value');
        if($prices){
            $priceExplode = explode(' $', $prices[0]->innertext);
            if (isset($priceExplode[1])){
                $result['minPrice'] = $priceExplode[1];
            }
        }

        $result['pageMax'] = 1;
        $pageMax = $dom->find('#pagination-max');
        if ($pageMax) {
            $result['pageMax'] = $pageMax[0]->innertext;
        }

        return $result;
    }


    public function setParameter($parameter, $value){
        $this->parameters[$parameter] = $value;
    }

    public function getParameter($key)
    {
        return isset($this->parameters[$key]) ? $this->parameters[$key] : null;
    }

    protected function urlGenerate($category, $page, $dataParameters = array())
    {
        $urlParameter = '';
        foreach($dataParameters as $parameter => $value){
            if ($value) {
                $urlParameter .= "{$parameter}={$value}&";
            }
        }
        $urlParameter = trim($urlParameter, '&');
        if($page){
            $url = "http://www.aliexpress.com/premium/category/{$category}/{$page}.html?" . $urlParameter;
        } else {
            $url = "http://www.aliexpress.com/premium/category/{$category}.html?" . $urlParameter;
        }
        return $url;
    }

    protected function saveProducts($data){
        //Формируе связи
        $store = $this->getContainer()->get('doctrine')
            ->getRepository('ChiToPikStoreBundle:Store')
            ->find(1);
        $category = $this->getContainer()->get('doctrine')
            ->getRepository('ChiToPikStoreBundle:Category')
            ->find($this->getCategoryId());
        $categoryStore = $this->getContainer()->get('doctrine')
            ->getRepository('ChiToPikStoreBundle:CategoryStore')
            ->findOneBy(array('categoryStoreId'=>$this->getCategoryStoreId()));

        //Масив продуктов для проверки наличия в БД
        $productIds = array();
        foreach ($data as $product) {
                $productIds[] = (int)$product['product-id'];
        }
        $productExist = $this->getContainer()->get('doctrine')
            ->getRepository('ChiToPikStoreBundle:Product')
            ->findBy(array ('productId' => $productIds));

        //Формируем масив id категорий которые уже есть в БД
        $exist = array();
        foreach($productExist as $val){
            $exist[] = $val->getProductId();
        }

        //формируем масив опций которым меньше 2 дней для исключения на запись
        $productsOptionsExpired = array();
        if ($exist) {
            $em = $this->getContainer()->get('doctrine')->getManager();
            $query = $em->createQuery(
                'SELECT po
                FROM ChiToPikStoreBundle:productOptions po
                WHERE DATE_DIFF(CURRENT_DATE(), po.dateTimeCreated) < 2
                AND po.productId IN (:productId)'
            )->setParameter('productId', $productExist);;

            $productsOptionsExpiredArray = $query->getResult();
            foreach($productsOptionsExpiredArray as $val){
                $productsOptionsExpired[]=$val->getProductId();
            }
        }

        //Погототавливаем данные на запись
        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        foreach($data as $value){
            if (false === $key = array_search($value['product-id'], $exist)) {
                $product = new Product();
                $shipping = $this->getContainer()->get('doctrine')
                    ->getRepository('ChiToPikStoreBundle:Shipping')
                    ->find($value['shipping']);
                $product
                    ->setProductId((int)$value['product-id'])
                    ->setCategoryId($category)
                    ->setStoreId($store)
                    ->setCategoryStoreId($categoryStore)
                    ->setName($value['name'])
                    ->setProductUrl($value['product-url'])
                    ->setPhoto($value['img-src'])
                    ->setShippingId($shipping)
                    ->setDateTimeCreated(new \ DateTime (" now "));
                $em->persist($product);
            } else {
                $product = $productExist[$key];
            }

            if (!in_array($product, $productsOptionsExpired)) {
                $productOptions = new ProductOptions();
                $productOptions
                    ->setProductId($product)
                    ->setDateTimeCreated(new \ DateTime (" now "))
                    ->setCountFeedBack($value['feed-back'])
                    ->setCountOrders($value['orders'])
                    ->setPrice($value['price-value'])
                    ->setPriceMax($value['price-max'])
                    ->setStarRating($value['rating'])
                    ->setPriceUnit($value['price-unit']);
                $em->persist($productOptions);
            }
        }
        //Выполняем запрос
        $em->flush();
        $em->clear();
    }

    protected function parseProduct($url){
        $content = file_get_contents($url);
        $dom = HtmlDomParser::str_get_html($content);

        $result = array();
        foreach ($dom->find('.list-item') as $item) {
            $data = array(
                'product-id'  => '',
                'name'        => '',
                'img-src'     => '',
                'product-url' => '',
                'price-value' => '',
                'price-max'   => '',
                'price-unit'  => '',
                'rating'      => '',
                'feed-back'   => '',
                'orders'      => '',
                'seller'      => '',
                'shipping'    => '',
                'min-lot'     => '',
            );

            $arrayId = explode('|', $item->qrdata);
            $data['product-id'] = $arrayId[1];
            if ($img = $item->find('.picCore')){
                $data['img-src'] = !empty($img[0]->src) ? $img[0]->src : $img[0]->getAttribute('image-src');
            }
            if ($name = $item->find('.info h3 a')){
                $data['name'] = $name[0]->title;
                $data['product-url'] = $name[0]->href;
            }
            if($priceValue = $item->find('.info .price .value')){
                $arrayPrise = explode(' $', $priceValue[0]->innertext);
                if (mb_strpos($arrayPrise[1], ' - ', null,'UTF-8')){
                    $arrayPriseValue = explode(' - ', $arrayPrise[1]);
                    $data['price-value'] = $arrayPriseValue[0];
                    $data['price-max'] = $arrayPriseValue[1];
                } else {
                    $data['price-value'] = $arrayPrise[1];
                }
            }
            if($priceUnit = $item->find('.info .price .unit')){
                $data['price-unit'] = $priceUnit[0]->innertext;
            }
            if($freeShipping = $item->find('.info .free-s')){
                $data['shipping'] = 1;
            }
            if($paidShipping = $item->find('.info .pnl-shipping')){
                $data['shipping'] = 2;
            }
            if ($minOrder = $item->find('.info .min-order')){
                $data['min-order'] = trim($minOrder[0]->innertext);
            }
            if ($seller = $item->find('.info .store-name a')){
                $data['seller'] = $seller[0]->title;
            }
            if ($rating = $item->find('.info .star')){
                $data['rating'] = $rating[0]->title;
                preg_match_all("/Rating:\s(.*)\sout/", $rating[0]->title, $match);
                $data['rating'] = isset($match[1][0]) ? $match[1][0] : '';
            }
            if ($feedBack = $item->find('.info .rate-num')){
                $data['feed-back'] = str_replace(array('(',')'), '', $feedBack[0]->innertext);
            }
            if($orders = $item->find('.info .order-num em')){
                preg_match_all("/Orders\s\((.*)\)/", $orders[0]->innertext, $match);
                $data['orders'] = isset($match[1][0]) ? $match[1][0] : '';
            }

            $result[] = $data;
        }
        return $result;
    }

}