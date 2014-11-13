<?php

namespace Demofony2\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('Demofony2UserBundle:Default:index.html.twig', array('name' => $name));
    }
}
