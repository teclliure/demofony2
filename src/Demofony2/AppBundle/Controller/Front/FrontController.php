<?php

namespace Demofony2\AppBundle\Controller\Front;

use Demofony2\AppBundle\Entity\Suggestion;
use Demofony2\AppBundle\Form\Type\Front\SuggestionFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class FrontController
 * @category Controller
 * @package  Demofony2\AppBundle\Controller\Front
 * @author   David Romaní <david@flux.cat>
 */
class FrontController extends Controller
{
    /**
     * @Route("/", name="demofony2_front_homepage")
     * @param Request $request
     * @return Response
     */
    public function homepageAction(Request $request)
    {
        $suggestion = new Suggestion();
        $form = $this->createForm(
            new SuggestionFormType(),
            $suggestion,
            array('isLogged' => $this->isGranted('ROLE_USER'))
        );

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($suggestion);
            $em->flush();
            $this->addFlash('success', 'Your message has been sent!');

            return $this->redirectToRoute('demofony2_front_homepage');
        }

        return $this->render('Front/homepage.html.twig', array(
            'form'                          => $form->createView(),
            'transparencyCurrentActivity'   => array(),
            'participationCurrentActivity'  => array(),
        ));
    }
}
