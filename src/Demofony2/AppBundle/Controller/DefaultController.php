<?php

namespace Demofony2\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('Demofony2AppBundle:Default:index.html.twig', array('name' => $name));
    }
}
