<?php

namespace Demofony2\AppBundle\Controller\Front;

use Demofony2\AppBundle\Entity\CategoryTransparency;
use Demofony2\AppBundle\Entity\DocumentTransparency;
use Demofony2\AppBundle\Entity\Suggestion;
use Demofony2\AppBundle\Form\Type\Front\SuggestionFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Class TransparencyController
 * @category Controller
 * @author   David Romaní <david@flux.cat>
 */
class TransparencyController extends Controller
{
    /**
     * @param Request $request
     * @Route("/transparency/", name="demofony2_front_transparency")
     * @return Response
     */
    public function transparencyAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('Demofony2AppBundle:CategoryTransparency')->findBy([], ['position' => 'ASC']);
        $documents = $em->getRepository('Demofony2AppBundle:DocumentTransparency')->getMoreInteresting();

        return $this->render('Front/transparency.html.twig', array(
            'categories'      => $categories,
            'moreInteresting' => $documents,
        ));
    }

    /**
     * @param Request $request
     * @param CategoryTransparency $category
     *
     * @Route("/transparency/{slug}/", name="demofony2_front_transparency_list")
     * @ParamConverter("category", options={"mapping": {"slug": "slug"}})
     *
     * @return Response
     */
    public function transparencyListAction(Request $request, CategoryTransparency $category)
    {
        return $this->render('Front/transparency/list.html.twig', array(
            'category'  => $category,
            'documents' => $category->getDocuments(),
        ));
    }

    /**
     * @param Request $request
     * @param CategoryTransparency $category
     * @param DocumentTransparency $document
     * @Route("/transparency/{category}/{document}", name="demofony2_front_transparency_detail")
     * @ParamConverter("category", options={"mapping": {"category": "slug"}})
     * @ParamConverter("document", options={"mapping": {"document": "slug"}})
     * @return Response
     */
    public function transparencyDetailAction(Request $request, CategoryTransparency $category, DocumentTransparency $document)
    {
        $document->addVisit();
        $this->getDoctrine()->getManager()->flush();

        return $this->render('Front/transparency/detail.html.twig', array(
            'category' => $category,
            'document' => $document,
        ));
    }
}
