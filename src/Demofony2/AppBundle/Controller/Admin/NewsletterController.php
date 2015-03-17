<?php

namespace Demofony2\AppBundle\Controller\Admin;

use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Sonata\AdminBundle\Exception\ModelManagerException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class NewsletterController extends Controller
{
    public function newsletterSendAction()
    {
        $id = $this->get('request')->get($this->admin->getIdParameter());

        $object = $this->admin->getObject($id);

        if (!$object) {
            throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $id));
        }

        //todo send

        $this->addFlash('sonata_flash_success', 'Newsletter enviat');

        return new RedirectResponse($this->admin->generateUrl('list'));
    }

    public function newsletterTestAction()
    {
        $id = $this->get('request')->get($this->admin->getIdParameter());

        $object = $this->admin->getObject($id);

        if (!$object) {
            throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $id));
        }

        //todo send

        $this->addFlash('sonata_flash_success', 'Newsletter de test enviada: ' . $this->getUser()->getEmail());

        return new RedirectResponse($this->admin->generateUrl('list'));
    }
}
