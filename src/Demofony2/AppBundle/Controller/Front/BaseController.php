<?php

namespace Demofony2\AppBundle\Controller\Front;

use Demofony2\AppBundle\Entity\Page;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class BaseController
 *
 * @category Controller
 * @package  Demofony2\AppBundle\Controller\Front
 * @author   David Romaní <david@flux.cat>
 */
abstract class BaseController extends Controller
{
    /**
     * @param $url string unique
     *
     * @throws NotFoundHttpException
     * @return Page
     */
    protected function getCmsPage($url)
    {
        $em = $this->getDoctrine()->getManager();
        $page = $em->getRepository('Demofony2AppBundle:Page')->findOneBy(array('url' => $url));

        if (!$page) {
            throw $this->createNotFoundException();
        }

        return $page;
    }
}
