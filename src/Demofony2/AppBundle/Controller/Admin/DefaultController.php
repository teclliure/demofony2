<?php

namespace Demofony2\AppBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/dashboard/", name="demofony2_admin_dashboard")
     */
    public function dashboardAction()
    {
        return $this->render(':Admin/Default:dashboard.html.twig');
    }
}
