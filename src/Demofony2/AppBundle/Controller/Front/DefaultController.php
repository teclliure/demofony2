<?php

namespace Demofony2\AppBundle\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class DefaultController
 *
 * @category Controller
 * @package  Demofony2\AppBundle\Controller\Front
 * @author   David Romaní <david@flux.cat>
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="demofony2_front_homepage")
     */
    public function homepageAction()
    {
        return $this->render('::Front/homepage.html.twig');
    }

    /**
     * @Route("/{_locale}/", name="demofony2_front_homepage_i18n")
     */
    public function homepageI18nAction()
    {
        return $this->render('::Front/homepage.html.twig');
    }

    /**
     * @Route("/{_locale}/", name="demofony2_front_government_i18n")
     */
    public function governmentAction()
    {
        return $this->render('::Front/homepage.html.twig');
    }
}
