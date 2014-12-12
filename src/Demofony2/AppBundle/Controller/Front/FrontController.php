<?php

namespace Demofony2\AppBundle\Controller\Front;

use Demofony2\AppBundle\Entity\Suggestion;
use Demofony2\AppBundle\Form\Type\SuggestionType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class FrontController
 *
 * @category Controller
 * @package  Demofony2\AppBundle\Controller\Front
 * @author   David Romaní <david@flux.cat>
 */
class FrontController extends Controller
{
    /**
     * @Route("/", name="demofony2_front_homepage")
     */
    public function homepageAction(Request $request)
    {
        // fake
        $levels = array(
            'uab' => 10,
            'ita' => 20,
            'law' => 15,
        );
        $suggestion = new Suggestion();
        $form = $this->createForm(new SuggestionType(), $suggestion);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($suggestion);
            $em->flush();

            $this->addFlash('success', 'Your message has been sent!');
        }

        return $this->render('Front/homepage.html.twig', array(
                'levels' => $levels,
                'form'   => $form->createView(),
            ));
    }

    /**
     * @Route("/government/", name="demofony2_front_government")
     */
    public function governmentAction()
    {
        return $this->render('Front/government.html.twig');
    }

    /**
     * @Route("/transparency/", name="demofony2_front_transparency")
     */
    public function transparencyAction()
    {
        // fake
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
