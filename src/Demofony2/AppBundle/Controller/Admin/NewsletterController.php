<?php

namespace Demofony2\AppBundle\Controller\Admin;

use Demofony2\AppBundle\Entity\Newsletter;
use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use DateTime;

class NewsletterController extends Controller
{
    public function newsletterSendAction()
    {
        $id = $this->get('request')->get($this->admin->getIdParameter());

        $object = $this->admin->getObject($id);

        if (!$object instanceof Newsletter) {
            throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $id));
        }

        $this->getMailManager()->sendNewsletter($object);
        $object->setSended(true);
        $object->setSendedAt(new \DateTime('NOW'));
        $this->addFlash('sonata_flash_success', 'Newsletter enviat');
        $this->getDoctrine()->getManager()->flush();

        return new RedirectResponse($this->admin->generateUrl('list'));
    }

    public function newsletterTestAction()
    {
        $id = $this->get('request')->get($this->admin->getIdParameter());

        $object = $this->admin->getObject($id);

        if (!$object instanceof Newsletter) {
            throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $id));
        }

        $this->getMailManager()->sendNewsletterTest($object, $this->getUser());

        $this->addFlash('sonata_flash_success', 'Newsletter de test enviada: '.$this->getUser()->getEmail());

        return new RedirectResponse($this->admin->generateUrl('list'));
    }

    public function getMailManager()
    {
        return $this->get('app.mail_manager');
    }
}
