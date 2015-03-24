<?php

namespace Demofony2\AppBundle\Controller\Front;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class CmsController
 */
class CmsController extends BaseController
{
    /**
     * @Route("/easy-guide/", name="demofony2_front_cms_easy_guide")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function easyGuideAction()
    {
        $page = $this->getCmsPage('guia-facil');

        return $this->render(':Front/cms:easy_guide.html.twig', ['page' => $page]);
    }

    /**
     * @Route("/regulation/", name="demofony2_front_cms_regulation")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function regulationAction()
    {
        $page = $this->getCmsPage('reglament');

        return $this->render(':Front/cms:regulation.html.twig', ['page' => $page]);
    }

    /**
     * @Route("/ita/", name="demofony2_front_cms_ita")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function itaAction()
    {
        $page = $this->getCmsPage('ita');

        return $this->render(':Front/cms:ita.html.twig', ['page' => $page]);
    }

    /**
     * @Route("/uab/", name="demofony2_front_cms_uab")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function uabAction()
    {
        $page = $this->getCmsPage('uab');

        return $this->render(':Front/cms:uab.html.twig', ['page' => $page]);
    }

    /**
     * @Route("/transparency/transparency-laws/", name="demofony2_front_cms_transparency_law")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function transparencyLawAction()
    {
        $page = $this->getCmsPage('llei-de-transparencia');

        return $this->render(':Front/cms:transparency-law.html.twig', ['page' => $page]);
    }

    /**
     * @Route("/open-government/", name="demofony2_front_cms_open_goverment")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function openGovernmentAction()
    {
        $page = $this->getCmsPage('open-government');

        return $this->render(':Front/cms:open-government.html.twig', ['page' => $page]);
    }

    /**
     * @Route("/transparency/summary-account/", name="demofony2_front_cms_rendering_account")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function renderingAccountAction()
    {
        $page = $this->getCmsPage('rendicio-de-comptes');

        return $this->render(':Front/cms:rendering-account.html.twig', ['page' => $page]);
    }

    /**
     * @Route("/transparency/collaborate/", name="demofony2_front_cms_collaborates")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function collaboratesAction()
    {
        $page = $this->getCmsPage('colabora');

        return $this->render(':Front/cms:collaborates.html.twig', ['page' => $page]);
    }

    /**
     * @Route("/transparency/public-information/", name="demofony2_front_cms_public_information")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function publicInformationAction()
    {
        $page = $this->getCmsPage('informacio-publica');

        return $this->render(':Front/cms:public-information.html.twig', ['page' => $page]);
    }
}
