<?php

namespace ChiToPik\StoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('ChiToPikStoreBundle:Default:index.html.twig', array('name' => $name));
    }
}
