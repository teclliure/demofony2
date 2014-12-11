<?php

namespace Demofony2\AppBundle\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class ComplementsController include common parts
 *  - privacy
 *  - legal
 *  - credits
 *
 * @category Controller
 * @package  Demofony2\AppBundle\Controller\Front
 * @author   David Romaní <david@flux.cat>
 */
class ComplementsController extends Controller
{
    /**
     * @Route("/privacy/", name="demofony2_front_privacy")
     */
    public function privacyAction()
    {
        return $this->render('Front/complements/privacy.html.twig');
    }

    /**
     * @Route("/legal/", name="demofony2_front_legal")
     */
    public function legalAction()
    {
        return $this->render('Front/complements/legal.html.twig');
    }

    /**
     * @Route("/credits/", name="demofony2_front_credits")
     */
    public function creditsAction()
    {
        return $this->render('Front/complements/credits.html.twig');
    }
}
