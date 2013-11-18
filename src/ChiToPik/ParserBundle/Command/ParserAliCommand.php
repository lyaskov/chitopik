<?php
namespace ChiToPik\ParserBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Sunra\PhpSimple\HtmlDomParser;
use ChiToPik\StoreBundle\Controller\TestDBController;
use ChiToPik\StoreBundle\Entity\Product;
use ChiToPik\StoreBundle\Entity\Category;
use ChiToPik\StoreBundle\Entity\Store;


class ParserAliCommand extends ContainerAwareCommand {
    private $time;

    public function configure(){
        $this->time = time();
        $this
            ->setName('parser:ali')
            ->setDescription('Parser AliExpress')
            ->addArgument('category', InputArgument::OPTIONAL, 'Who do you want to greet?')
//            ->addOption('yell', null, InputOption::VALUE_NONE, 'If set, the task will yell in uppercase letters')
        ;

    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $category = $input->getArgument('category');
        if ($category) {
            for($i=0;$i<1;$i++){
            $text = $this->getContentByUrl('http://www.aliexpress.com/premium/category/' . $category . '/'.$i.'.html');
            }
        } else {
            $text = "";
        }

//        if ($input->getOption('yell')) {
//            $text = strtoupper($text);
//        }

        $output->writeln($text);
        $output->writeln(time() - $this->time);

    }

    private function getContentByUrl($url)
    {
        return $this->getContentName(file_get_contents($url));
    }

    private function getContentName($content){
        $dom = HtmlDomParser::str_get_html( $content );

        foreach ($dom->find('.son-list li') as $el){
            $item = $el->find('.info .history-item');
            $p['title'] = $item[0]->title;
            $item = $el->find('.info .price em');
            $p['price'] = $item[0]->innertext;
            $item = $el->find('.info a.history-item');
            $p['url'] = $item[0]->href;
            $item = $el->find('img.picCore');
            $p['photo_url'] = $item[0]->src;

            print_r(implode('=>',$p).PHP_EOL);

            $idProd = $this->AddProduct($p);

            print_r(PHP_EOL."Saved in:".$idProd.PHP_EOL);
        }
    }

    public function AddProduct($data){
        $product = new Product();
        $category = $this->getContainer()->get('doctrine')
            ->getRepository('ChiToPikStoreBundle:Category')
            ->find(1);
        $store = $this->getContainer()->get('doctrine')
            ->getRepository('ChiToPikStoreBundle:Store')
            ->find(1);

        $product->setName($data['title']);
        $product->setCategoryId($category);
        $product->setStoreId($store);
        $product->setProductUrl($data['url']);
        $product->setPhoto($data['photo_url']);

        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        $em -> persist($product);
        $em -> flush();

        return $product->getProductId();
    }

} 