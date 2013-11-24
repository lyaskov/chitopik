<?php

namespace ChiToPik\ParserBundle\Command;

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
        $category = $input->getArgument('category');
        $dataParameters['minPrice'] = $input->getArgument('min-price');
        $dataParameters['maxPrice'] = $input->getArgument('max-price');
        $dataParameters['shipCountry'] = 'ru';

        $url = $this->urlGenerate($category, 1, $dataParameters);
        $this->parseProduct($url);

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
        $url = "http://www.aliexpress.com/premium/category/{$category}/{$page}.html?" . $urlParameter;
        return $url;
    }

    protected function parseProduct($url){
        $content = file_get_contents($url);
        $dom = HtmlDomParser::str_get_html($content);

        foreach($dom->find('.list-item') as $item){
            $data = array(
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
                $data['shipping'] = 0;
            }
            if($paidShipping = $item->find('.info .pnl-shipping')){
                $data['shipping'] = 1;
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

            var_dump($data);
        }
    }

}