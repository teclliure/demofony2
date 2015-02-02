<?php

namespace Demofony2\AppBundle\Controller\Front;

use Demofony2\AppBundle\Entity\Page;
use Demofony2\AppBundle\Entity\Suggestion;
use Demofony2\AppBundle\Form\Type\Front\SuggestionFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Class CmsController
 */
class CmsController extends Controller
{
    /**
     * @Route("/easy-guide/", name="demofony2_front_easy_guide")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function easyGuideAction()
    {
       $page = $this->getPage('guia-facil');

       return $this->render(':Front/cms:easy_guide.html.twig', ['page' => $page]);
    }

    protected function getPage($url)
    {
        $em = $this->getDoctrine()->getManager();
        $page = $em->getRepository('Demofony2AppBundle:Page')->findOneBy(array('url' => $url));

        if (!$page) {
            throw $this->createNotFoundException();
        }

        return $page;
    }
}
