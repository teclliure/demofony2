<?php

namespace Demofony2\AppBundle\Controller\Front;

use Demofony2\AppBundle\Entity\CategoryTransparency;
use Demofony2\AppBundle\Entity\DocumentTransparency;
use Demofony2\AppBundle\Entity\LawTransparency;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Class TransparencyController
 * @category Controller
 * @author   David Romaní <david@flux.cat>
 */
class TransparencyController extends BaseController
{
    /**
     * @Route("/transparency/", name="demofony2_front_transparency")
     *
     * @param  Request  $request
     * @return Response
     */
    public function transparencyAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('Demofony2AppBundle:CategoryTransparency')->findBy([], ['position' => 'ASC']);
        $documents = $em->getRepository('Demofony2AppBundle:DocumentTransparency')->getMoreInteresting();

        return $this->render('Front/transparency.html.twig', array(
            'categories'      => $categories,
            'interestingDocs' => $documents,
            'cms'             => array(
                'accounts'      => $this->getCmsPage('rendicio-de-comptes'),
                'collaborate'   => $this->getCmsPage('colabora'),
                'laws'          => $this->getCmsPage('llei-de-transparencia'),
                'info'          => $this->getCmsPage('informacio-publica'),
            ),
        ));
    }

    /**
     * @Route("/transparency/{slug}/", name="demofony2_front_transparency_list")
     * @ParamConverter("category", options={"mapping": {"slug": "slug"}})
     *
     * @param Request              $request
     * @param CategoryTransparency $category
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
     * @Route("/transparency/{category}/{document}/", name="demofony2_front_transparency_detail")
     * @ParamConverter("category", options={"mapping": {"category": "slug"}})
     * @ParamConverter("document", options={"mapping": {"document": "slug"}})
     *
     * @param Request              $request
     * @param CategoryTransparency $category
     * @param DocumentTransparency $document
     *
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

    /**
     * @Route("/transparency/law/{law}/", name="demofony2_front_transparency_law_detail")
     * @ParamConverter("law", options={"mapping": {"law": "id"}})
     *
     * @param Request         $request
     * @param LawTransparency $law
     *
     * @return Response
     */
    public function lawDetailAction(Request $request, LawTransparency $law)
    {
        return $this->render('Front/transparency/law-detail.html.twig', array(
            'law' => $law,
        ));
    }
}
