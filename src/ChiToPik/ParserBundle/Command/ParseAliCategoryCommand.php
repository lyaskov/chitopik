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

class ParseAliCategoryCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this
            ->setName('parser:ali:category')
            ->setDescription("Parser AliExpres All Categories")
            ->addArgument('category', InputArgument::OPTIONAL, 'Which  number of category do you want to pase?')
            ->addOption('all',null,InputOption::VALUE_NONE, 'Parse all category from site AliExpress');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $category = $input->getArgument('category');
        if ($input->getOption('all')) {
            $category = 'all';
        }
        if ($category == 'all') {
            $this->parseAllCategory();
        } else {
            $this->parseCategory($category);
        }
    }

    protected function parseAllCategory(){
        $url = "http://www.aliexpress.com/all-wholesale-products.html";
        $content = file_get_contents($url);
        $dom = HtmlDomParser::str_get_html($content);
        foreach($dom->find("a.main-category") as $element){
            $urlCategory = $element->href;
            http://www.aliexpress.com/category/200000369/Car-Electronics.html
            preg_match_all("/http:\/\/www\.aliexpress\.com\/category\/(\d+)\/.*html/", $urlCategory, $matches);
            $this->parseCategory($matches[1][0]);
        }
    }

    /**
     * Парсим дерево подкатегории для заданой категории
     * Результат отправляем на запись в БД
     *
     * @param string $category  - id категории на сайте
     * @return array список подкатегорий спаршеных с сайта aliexpres
     */
    protected function parseCategory($category){
        echo "Start parse for {$category}" . PHP_EOL;
        $url = "http://www.aliexpress.com/premium/category/{$category}/html";
        $content = file_get_contents($url);
        $dom = HtmlDomParser::str_get_html($content);

        $categoryList[$category] = array();
        foreach($dom->find(".son-category ul li") as $element){
            $categoryItem = $element->find('a');
            $categoryNum = $element->find('.num');
            $categoryList[$category][] = array(
                'name' => $categoryItem[0]->innertext,
                'url'  => $categoryItem[0]->href,
                'id' => (int)$categoryItem[0]->qrdata,
                'count' => str_replace(array('(',')'),'',$categoryNum[0]->innertext),
            );
        }
        $countCategory = count($categoryList[$category]);
        echo "Founded {$countCategory} categories" . PHP_EOL;
        //Сохраняем категории и для подкатегорий запускаем снова парсер
        if ($categoryList[$category]) {
            $this->saveCategories($categoryList);
            foreach ($categoryList[$category] as $val) {
                $this->parseCategory($val['id']);
            }
        }

        return true;
    }

    /**
     * Сохраняем категории в базу
     *
     * @param array $data Масив категорий
     */
    protected function saveCategories($data){
        $store = $this->getContainer()->get('doctrine')
            ->getRepository('ChiToPikStoreBundle:Store')
            ->find(1);

        //Масив категорий для проверки наличия в БД
        $categoryIds = array();
        foreach ($data as $parentId => $categories) {
            foreach ($categories as $category) {
                $categoryIds[] = (int)$category['id'];
            }
        }
        $categoryExist = $this->getContainer()->get('doctrine')
            ->getRepository('ChiToPikStoreBundle:CategoryStore')
            ->findBy(array ('categoryStoreId' => $categoryIds));

        //Формируем масив id категорий которые уже есть в БД
        $exist = array();
        foreach($categoryExist as $val){
            $exist[] = $val->getCategoryStoreID();
        }
        // Сохраняем новые категории в БД
        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        foreach ($data as $parentId => $categories) {
            echo "Start save categories, parent id {$parentId}" . PHP_EOL;
            $i = 0; $countCategory = count($categories);
            foreach ($categories as $category) {
                if (!in_array($category['id'], $exist)) {
                    $i++;
                    $categoryStore = new CategoryStore();
                    $categoryStore
                        ->setName($category['name'])
                        ->setStoreId($store)
                        ->setCategoryStoreId($category['id'])
                        ->setCategoryUrl($category['url'])
                        ->setCountProducts($category['count'])
                        ->setParentId($parentId);

                    $em->persist($categoryStore);
                }
            }
            echo "Add to save {$i} categories from {$countCategory}" . PHP_EOL;
        }
        $em -> flush();
        $em -> clear();
        echo "Saved" . PHP_EOL;
    }

} 