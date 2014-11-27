<?php

namespace Demofony2\AppBundle\Manager;

use Demofony2\AppBundle\Entity\Comment;
use Demofony2\AppBundle\Entity\ProcessParticipation;
use Demofony2\AppBundle\Entity\ProposalAnswer;
use Demofony2\AppBundle\Form\Type\Api\CommentType;
use Demofony2\AppBundle\Form\Type\Api\VoteType;
use Demofony2\UserBundle\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Process\Process;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormTypeInterface;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpKernel\Exception\HttpException;
use FOS\RestBundle\Util\Codes;

class ProcessParticipationManager extends AbstractManager
{
    protected $formFactory;
    protected $voteChecker;

    /**
     * @param ObjectManager      $em
     * @param ValidatorInterface $validator
     * @param FormFactory        $formFactory
     */
    public function __construct(ObjectManager $em, ValidatorInterface $validator, FormFactory $formFactory, VotePermissionCheckerManager $vpc)
    {
        parent::__construct($em, $validator);
        $this->formFactory = $formFactory;
        $this->voteChecker = $vpc;
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

    /**
     * @param ProcessParticipation $processParticipation
     * @param int                  $page
     * @param int                  $limit
     *
     * @return array
     */
    public function getComments(ProcessParticipation $processParticipation, $page = 1, $limit = 10)
    {
        $id = $processParticipation->getId();
        $commentRepository = $this->em->getRepository('Demofony2AppBundle:Comment');
        $comments = $commentRepository->getCommentsByProcessParticipation($id, $page, $limit, false);
        $count = $commentRepository->getCommentsByProcessParticipation($id, $page, $limit, true);

        return array($comments, $count);
    }

    public function postComment(ProcessParticipation $processParticipation, Request $request)
    {
        $form = $this->createForm(new CommentType(), null, array('action' => 'create'));
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
     * @param ProcessParticipation $processParticipation
     * @param Comment              $comment
     * @param Request              $request
     *
     * @return bool|View
     */
    public function putComment(ProcessParticipation $processParticipation, Comment $comment, Request $request)
    {
        if ($processParticipation !== $comment->getProcessParticipation()) {
            throw new HttpException(Codes::HTTP_BAD_REQUEST, 'Comment not belongs to process participation ');
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
     * @param ProcessParticipation $processParticipation
     * @param Comment              $comment
     * @param int                  $page
     * @param int                  $limit
     *
     * @return array
     */
    public function getChildrenInComment(ProcessParticipation $processParticipation, Comment $comment, $page, $limit)
    {
        $commentRepository = $this->em->getRepository('Demofony2AppBundle:Comment');
        $comments = $commentRepository->getChildrenCommentByProcessParticipation(
            $processParticipation->getId(),
            $comment->getId(),
            $page,
            $limit,
            false
        );
        $count = $commentRepository->getChildrenCommentByProcessParticipation(
            $processParticipation->getId(),
            $comment->getId(),
            $page,
            $limit,
            true
        );

        return array($comments, $count);
    }

    public function postVote(
        ProcessParticipation $processParticipation,
        ProposalAnswer $proposalAnswer,
        Request $request
    ) {
        $form = $this->createForm(new VoteType());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->voteChecker->checkIfProcessParticipationIsInVotePeriod($processParticipation);
            $this->voteChecker->checkUserHasVoteInProcessParticipation($processParticipation);
            $vote = $form->getData();
            $proposalAnswer->addVote($vote);
            $this->persist($vote);

            return $vote;
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
