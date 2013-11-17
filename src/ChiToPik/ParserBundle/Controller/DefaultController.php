<?php

namespace ChiToPik\ParserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('ChiToPikParserBundle:Default:index.html.twig', array('name' => $name));
    }
}
