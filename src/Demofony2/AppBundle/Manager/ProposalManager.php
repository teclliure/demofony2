<?php

namespace Demofony2\AppBundle\Manager;

use Demofony2\AppBundle\Entity\Comment;
use Demofony2\AppBundle\Entity\Proposal;
use Demofony2\AppBundle\Form\Type\Api\CommentType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormTypeInterface;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpKernel\Exception\HttpException;
use FOS\RestBundle\Util\Codes;

class ProposalManager extends AbstractManager
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
        return $this->em->getRepository('Demofony2AppBundle:Proposal');
    }

    /**
     * @return ProcessParticipation
     */
    public function create()
    {
        return new Proposal();
    }

    /**
     * @param Proposal $proposal
     * @param int      $page
     * @param int      $limit
     *
     * @return array
     */
    public function getComments(Proposal $proposal, $page = 1, $limit = 10)
    {
        $id = $proposal->getId();
        $commentRepository = $this->em->getRepository('Demofony2AppBundle:Comment');
        $comments = $commentRepository->getCommentsByProposal($id, $page, $limit, false);
        $count = $commentRepository->getCommentsByProposal($id, $page, $limit, true);

        return array($comments, $count);
    }

    public function postComment(Proposal $proposal, Request $request)
    {
        $form = $this->createForm(new CommentType(), null, array('action' => 'create'));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /* @var Comment $entity*/
            $entity = $form->getData();
            $entity->setProposal($proposal);
            $this->persist($entity);

            return $entity;
        }

        return View::create($form, 400);
    }

    /**
     * @param Proposal $proposal
     * @param Comment  $comment
     * @param Request  $request
     *
     * @return bool|View
     */
    public function putComment(Proposal $proposal, Comment $comment, Request $request)
    {
        if ($proposal !== $comment->getProposal()) {
            throw new HttpException(Codes::HTTP_BAD_REQUEST, 'Comment not belongs to proposal ');
        }

        $form = $this->createForm(new CommentType(), $comment, array('method' => 'PUT', 'action' => 'edit'));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->flush($comment);

            return true;
        }

        return View::create($form, 400);
    }

    /**
     * @param Proposal $proposal
     * @param Comment  $comment
     * @param int      $page
     * @param int      $limit
     *
     * @return array
     */
    public function getChildrenInComment(Proposal $proposal, Comment $comment, $page, $limit)
    {
        $id = $proposal->getId();
        $commentRepository = $this->em->getRepository('Demofony2AppBundle:Comment');
        $comments = $commentRepository->getChildrenCommentByProposal($id, $comment->getId(), $page, $limit, false);
        $count = $commentRepository->getChildrenCommentByProposal($id, $comment->getId(), $page, $limit, true);

        return array($comments, $count);
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
