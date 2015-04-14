<?php

namespace Demofony2\AppBundle\Controller\Admin;

use Demofony2\AppBundle\Entity\Comment;
use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Sonata\AdminBundle\Exception\ModelManagerException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CommentController extends Controller
{
    public function batchActionRevise(ProxyQueryInterface $query)
    {
        if (false === $this->admin->isGranted('EDIT')) {
            throw new AccessDeniedException();
        }

        $modelManager = $this->admin->getModelManager();
        try {
            $this->batchRevised($this->admin->getClass(), $query);
            $this->addFlash('sonata_flash_success', 'flash_batch_delete_success');
        } catch (ModelManagerException $e) {
            $this->addFlash('sonata_flash_error', 'flash_batch_delete_error');
        }

        return new RedirectResponse($this->admin->generateUrl(
            'list',
            array('filter' => $this->admin->getFilterParameters())
        ));
    }

    public function batchRevised($class, ProxyQueryInterface $queryProxy)
    {
        $queryProxy->select('DISTINCT '.$queryProxy->getRootAlias());

        try {
            $entityManager = $this->getDoctrine()->getManager();

            $i = 0;
            foreach ($queryProxy->getQuery()->iterate() as $pos => $object) {
                $object[0]->setRevised(true);

                if ((++$i % 20) == 0) {
                    $entityManager->flush();
                    $entityManager->clear();
                }
            }

            $entityManager->flush();
            $entityManager->clear();
        } catch (\PDOException $e) {
            throw new ModelManagerException('', 0, $e);
        } catch (DBALException $e) {
            throw new ModelManagerException('', 0, $e);
        }
    }

    public function showPublicPageAction()
    {
        $id = $this->get('request')->get($this->admin->getIdParameter());

        $object = $this->admin->getObject($id);

        if (!$object) {
            throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $id));
        }

        $url = null;

        if ($object instanceof Comment && is_object($pp = $object->getProcessParticipation())) {

            $url = $this->generateUrl('demofony2_front_participation_discussions_edit', array('id' => $object->getId(), 'discussion' => $object->getTitleSlug()));
        }
        elseif ($object instanceof Comment && is_object($p = $object->getProposal())) {

            return;
        }
        elseif ($object instanceof Comment && is_object($p = $object->getCitizenForum())) {

            return;
        }

        return new RedirectResponse($url);
    }
}
