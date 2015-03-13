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
        // fakes
        $data = array(
            'lastUpdate' => new \DateTime(),
        );
        $levels = array(
            'uab' => 10,
            'ita' => 20,
            'law' => 15,
        );

        return $this->render('Front/transparency.html.twig', array('data' => $data, 'levels' => $levels));
    }
}
