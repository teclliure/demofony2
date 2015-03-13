<?php

namespace Demofony2\AppBundle\Controller\Front;

use Demofony2\AppBundle\Entity\Suggestion;
use Demofony2\AppBundle\Form\Type\Front\SuggestionFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class TransparencyController
 * @category Controller
 * @package  Demofony2\AppBundle\Controller\Front
 * @author   David Romaní <david@flux.cat>
 */
class TransparencyController extends Controller
{
    /**
     * @Route("/transparency/", name="demofony2_front_transparency")
     * @param Request $request
     * @return Response
     */
    public function transparencyAction(Request $request)
    {
        return $this->render('Front/transparency.html.twig', array(
            'categories'      => array(),
            'moreInteresting' => array(),
        ));
    }

    /**
     * @Route("/transparency/{category}", name="demofony2_front_transparency_list")
     * @param Request $request
     * @return Response
     */
    public function transparencyListAction(Request $request)
    {
        return $this->render('Front/transparency/list.html.twig', array(
            'category'  => array(),
            'documents' => array(),
        ));
    }

    /**
     * @Route("/transparency/{category}/{id}/{document}", name="demofony2_front_transparency_detail")
     * @param Request $request
     * @return Response
     */
    public function transparencyDetailAction(Request $request)
    {
        return $this->render('Front/transparency/detail.html.twig', array(
            'document' => array(),
        ));
    }
}
