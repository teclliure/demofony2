<?php

namespace Demofony2\AppBundle\Manager;

use Demofony2\AppBundle\Entity\ProcessParticipation;
use Demofony2\AppBundle\Form\Type\Api\CommentType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormTypeInterface;
use FOS\RestBundle\View\View;

class ProcessParticipationManager extends AbstractManager
{
    protected $formFactory;

    /**
     * @param ObjectManager      $em
     * @param ValidatorInterface $validator
     * @param FormFactory        $formFactory
     */
    public function __construct(ObjectManager $em, ValidatorInterface $validator, FormFactory $formFactory)
    {
        parent::__construct($em, $validator);
        $this->formFactory = $formFactory;
    }

    /**
     * @return \Demofony2\AppBundle\Repository\ProcessParticipationRepository
     */
    public function getRepository()
    {
        return $this->em->getRepository('Demofony2AppBundle:ProcessParticipation');
    }

    /**
     * @return ProcessParticipation
     */
    public function create()
    {
        return new ProcessParticipation();
    }

    public function getComments($id, $page, $limit)
    {
        return $this->getRepository()->getComments($id, $page, $limit);
    }

    public function postComment(ProcessParticipation $processParticipation, Request $request)
    {
        $form = $this->createForm(new CommentType());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entity = $form->getData();
            $entity->setProcessParticipation($processParticipation);
            $this->persist($entity);

            return $entity;
        }

        return View::create($form, 400);
    }

    /**
     * Creates and returns a Form instance from the type of the form.
     *
     * @param string|FormTypeInterface $type    The built type of the form
     * @param mixed                    $data    The initial data for the form
     * @param array                    $options Options for the form
     *
     * @return Form
     */
    protected function createForm($type, $data = null, array $options = array())
    {
        return $this->formFactory->create($type, $data, $options);
    }
}
