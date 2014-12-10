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
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Process\Process;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormTypeInterface;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpKernel\Exception\HttpException;
use FOS\RestBundle\Util\Codes;
use Demofony2\AppBundle\Entity\Vote;
use Demofony2\AppBundle\Entity\CommentVote;

class ProcessParticipationManager extends AbstractManager
{
    protected $formFactory;
    protected $voteChecker;

    /**
     * @param ObjectManager                $em
     * @param ValidatorInterface           $validator
     * @param FormFactory                  $formFactory
     * @param VotePermissionCheckerService $vpc
     */
    public function __construct(
        ObjectManager $em,
        ValidatorInterface $validator,
        FormFactory $formFactory,
        VotePermissionCheckerService $vpc
    ) {
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
            $this->voteChecker->checkIfProcessParticipationIsInVotePeriod($processParticipation);
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

    /**
     * @param ProcessParticipation $processParticipation
     * @param ProposalAnswer       $proposalAnswer
     * @param User                 $user
     * @param Request              $request
     *
     * @return View|mixed
     */
    public function postVote(
        ProcessParticipation $processParticipation,
        ProposalAnswer $proposalAnswer,
        User $user,
        Request $request
    ) {
        $this->checkConsistency($processParticipation, $proposalAnswer);
        $form = $this->createForm(new VoteType());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->voteChecker->checkIfProcessParticipationIsInVotePeriod($processParticipation);
            $this->voteChecker->checkUserHasVoteInProcessParticipation($processParticipation, $user);
            $vote = $form->getData();
            $proposalAnswer->addVote($vote);
            $this->persist($vote);

            return $vote;
        }

        return View::create($form, 400);
    }

    /**
     * @param ProcessParticipation $processParticipation
     * @param ProposalAnswer       $proposalAnswer
     * @param User                 $user
     * @param Request              $request
     *
     * @return Vote|View
     */
    public function editVote(
        ProcessParticipation $processParticipation,
        ProposalAnswer $proposalAnswer,
        User $user,
        Request $request
    ) {
        $vote = $this->getUserVote($processParticipation, $user);
        $this->checkConsistency($processParticipation, $proposalAnswer, $vote);
        $form = $this->createForm(new VoteType(), $vote, array('method' => 'PUT'));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->voteChecker->checkIfProcessParticipationIsInVotePeriod($processParticipation);
            $this->flush($vote);

            return true;
        }

        return View::create($form, 400);
    }

    /**
     * @param ProcessParticipation $processParticipation
     * @param ProposalAnswer       $proposalAnswer
     * @param User                 $user
     *
     * @return bool
     */
    public function deleteVote(
        ProcessParticipation $processParticipation,
        ProposalAnswer $proposalAnswer,
        User $user
    ) {
        $vote = $this->getUserVote($processParticipation, $user);
        $this->checkConsistency($processParticipation, $proposalAnswer, $vote);
        $this->voteChecker->checkIfProcessParticipationIsInVotePeriod($processParticipation);
        $this->remove($vote);

        return true;
    }

    /**
     * @param ProcessParticipation $processParticipation
     * @param Comment              $comment
     *
     * @return Comment
     */
    public function likeComment(ProcessParticipation $processParticipation, Comment $comment)
    {
        $this->voteChecker->checkIfProcessParticipationIsInVotePeriod($processParticipation);

        if (!$processParticipation->getComments()->contains($comment)) {
            throw new HttpException(Codes::HTTP_BAD_REQUEST, 'Comment not belongs to this process participation ');
        }

        $like = new CommentVote(true, $comment);
        $this->persist($like, false);
        $this->flush($like);
        $this->em->refresh($comment);


        return $comment;
    }

    /**
     * @param ProcessParticipation $processParticipation
     * @param Comment              $comment
     *
     * @return Comment
     */
    public function unLikeComment(ProcessParticipation $processParticipation, Comment $comment)
    {
        $this->voteChecker->checkIfProcessParticipationIsInVotePeriod($processParticipation);

        if (!$processParticipation->getComments()->contains($comment)) {
            throw new HttpException(Codes::HTTP_BAD_REQUEST, 'Comment not belongs to this process participation ');
        }

        $unlike = new CommentVote(false, $comment);
        $this->persist($unlike, false);
        $this->flush($unlike);
        $this->em->refresh($comment);

        return $comment;
    }

    /**
     * @param ProcessParticipation $processParticipation
     * @param Comment              $comment
     * @param User                 $user
     *
     * @return bool
     */
    public function deleteLikeComment(ProcessParticipation $processParticipation, Comment $comment, User $user)
    {
        if (!$processParticipation->getComments()->contains($comment)) {
            throw new HttpException(Codes::HTTP_BAD_REQUEST, 'Comment not belongs to this process participation ');
        }
        $vote = $this->em->getRepository('Demofony2AppBundle:CommentVote')->findOneBy(array('comment' => $comment, 'author'=>$user, 'value'=>true));

        if (!$vote) {
            throw new HttpException(Codes::HTTP_BAD_REQUEST, "User don't like this comment");
        }

        $this->remove($vote);
        $this->em->refresh($comment);

        return $comment;
    }

    /**
     * @param ProcessParticipation $processParticipation
     * @param Comment              $comment
     * @param User                 $user
     *
     * @return bool
     */
    public function deleteUnlikeComment(ProcessParticipation $processParticipation, Comment $comment, User $user)
    {
        if (!$processParticipation->getComments()->contains($comment)) {
            throw new HttpException(Codes::HTTP_BAD_REQUEST, 'Comment not belongs to this process participation ');
        }
        $vote = $this->em->getRepository('Demofony2AppBundle:CommentVote')->findOneBy(array('comment' => $comment, 'author'=>$user, 'value'=>false));

        if (!$vote) {
            throw new HttpException(Codes::HTTP_BAD_REQUEST, "User don't unlike this comment");
        }

        $this->remove($vote);
        $this->em->refresh($comment);

        return $comment;
    }


    /**
     * Check if proposal answer belongs to process participation and if vote belongs to proposalAnswer if vote is defined
     *
     * @param ProcessParticipation $processParticipation
     * @param ProposalAnswer       $proposalAnswer
     * @param Vote                 $vote
     */
    protected function checkConsistency(
        ProcessParticipation $processParticipation,
        ProposalAnswer $proposalAnswer,
        Vote $vote = null
    ) {
        if (!$processParticipation->getProposalAnswers()->contains($proposalAnswer)) {
            throw new HttpException(Codes::HTTP_BAD_REQUEST, 'Proposal answer not belongs to this process participation ');
        }

        if (isset($vote) && !$proposalAnswer->getVotes()->contains($vote)) {
            throw new HttpException(Codes::HTTP_BAD_REQUEST, 'Proposal answer has not got this vote ');
        }
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

  /**
     * Get vote from user
     *
     * @param ProcessParticipation $processParticipation
     * @param User $user
     *
     * @return Vote
     */
    protected function getUserVote(ProcessParticipation $processParticipation, User $user)
    {
       $vote = $this->em->getRepository('Demofony2AppBundle:Vote')->getVoteByUserInProcessParticipation($user->getId(), $processParticipation->getId(), false);

        if (!$vote) {
            throw new BadRequestHttpException('The user does not have a vote');
        }

        return $vote;
    }
}
