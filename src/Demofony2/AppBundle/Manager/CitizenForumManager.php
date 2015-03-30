<?php

namespace Demofony2\AppBundle\Manager;

use Demofony2\AppBundle\Entity\Comment;
use Demofony2\AppBundle\Entity\CitizenForum;
use Demofony2\AppBundle\Entity\ProposalAnswer;
use Demofony2\AppBundle\Form\Type\Api\CommentType;
use Demofony2\AppBundle\Form\Type\Api\VoteType;
use Demofony2\UserBundle\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormTypeInterface;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpKernel\Exception\HttpException;
use FOS\RestBundle\Util\Codes;
use Demofony2\AppBundle\Entity\Vote;
use Demofony2\AppBundle\Enum\ProcessParticipationStateEnum;

class CitizenForumManager extends AbstractManager
{
    protected $formFactory;
    protected $voteChecker;
    protected $commentVoteManager;

    /**
     * @param ObjectManager                $em
     * @param ValidatorInterface           $validator
     * @param FormFactory                  $formFactory
     * @param VotePermissionCheckerService $vpc
     * @param CommentVoteManager           $cvm
     */
    public function __construct(
        ObjectManager $em,
        ValidatorInterface $validator,
        FormFactory $formFactory,
        VotePermissionCheckerService $vpc,
        CommentVoteManager $cvm
    ) {
        parent::__construct($em, $validator);
        $this->formFactory = $formFactory;
        $this->voteChecker = $vpc;
        $this->commentVoteManager = $cvm;
    }

    /**
     * @return \Demofony2\AppBundle\Repository\ProcessParticipationRepository
     */
    public function getRepository()
    {
        return $this->em->getRepository('Demofony2AppBundle:CitizenForum');
    }

    /**
     * @return CitizenForum
     */
    public function create()
    {
        return new CitizenForum();
    }

    /**
     * @param CitizenForum $citizenForum
     * @param int          $page
     * @param int          $limit
     *
     * @return array
     */
    public function getComments(CitizenForum $citizenForum, $page = 1, $limit = 10)
    {
        $id = $citizenForum->getId();
        $commentRepository = $this->em->getRepository('Demofony2AppBundle:Comment');
        $comments = $commentRepository->getCommentsByCitizenForum($id, $page, $limit, false);
        $count = $commentRepository->getCommentsByCitizenForum($id, $page, $limit, true);

        return array($comments, $count);
    }

    /**
     * @param CitizenForum $citizenForum
     * @param Request      $request
     *
     * @return View|mixed
     */
    public function postComment(CitizenForum $citizenForum, Request $request)
    {
        $form = $this->createForm(new CommentType(), null, array('action' => 'create'));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entity = $form->getData();
            $entity->setCitizenForum($citizenForum);
            $this->persist($entity);

            return $entity;
        }

        return View::create($form, 400);
    }

    /**
     * @param CitizenForum $citizenForum
     * @param Comment      $comment
     * @param Request      $request
     *
     * @return bool|View
     */
    public function putComment(CitizenForum $citizenForum, Comment $comment, Request $request)
    {
        if ($citizenForum !== $comment->getCitizenForum()) {
            throw new HttpException(Codes::HTTP_BAD_REQUEST, 'Comment not belongs to citizen forum ');
        }

        $form = $this->createForm(new CommentType(), $comment, array('method' => 'PUT', 'action' => 'edit'));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->voteChecker->checkIfCitizenForumIsInVotePeriod($citizenForum);
            $this->flush($comment);

            return true;
        }

        return View::create($form, 400);
    }

    /**
     * @param CitizenForum $citizenForum
     * @param Comment      $comment
     * @param int          $page
     * @param int          $limit
     *
     * @return array
     */
    public function getChildrenInComment(CitizenForum $citizenForum, Comment $comment, $page, $limit)
    {
        $commentRepository = $this->em->getRepository('Demofony2AppBundle:Comment');
        $comments = $commentRepository->getChildrenCommentByCitizenForum(
            $citizenForum->getId(),
            $comment->getId(),
            $page,
            $limit,
            false
        );
        $count = $commentRepository->getChildrenCommentByCitizenForum(
            $citizenForum->getId(),
            $comment->getId(),
            $page,
            $limit,
            true
        );

        return array($comments, $count);
    }

    /**
     * @param CitizenForum         $citizenForum
     * @param ProposalAnswer       $proposalAnswer
     * @param User                 $user
     * @param Request              $request
     *
     * @return View|mixed
     */
    public function postVote(
        CitizenForum   $citizenForum,
        ProposalAnswer $proposalAnswer,
        User $user,
        Request $request
    ) {
        $this->checkConsistency($citizenForum, $proposalAnswer);
        $form = $this->createForm(new VoteType());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->voteChecker->checkIfCitizenForumIsInVotePeriod($citizenForum);
            $this->voteChecker->checkUserHasVoteInCitizenForum($citizenForum, $user);
            $vote = $form->getData();
            $proposalAnswer->addVote($vote);
            $this->persist($vote);

            return $vote;
        }

        return View::create($form, 400);
    }

    /**
     * @param CitizenForum         $citizenForum
     * @param ProposalAnswer       $proposalAnswer
     * @param User                 $user
     * @param Request              $request
     *
     * @return Vote|View
     */
    public function editVote(
        CitizenForum   $citizenForum,
        ProposalAnswer $proposalAnswer,
        User $user,
        Request $request
    ) {
        $vote = $this->getUserVote($citizenForum, $user);
        $this->checkConsistency($citizenForum, $proposalAnswer, $vote);
        $form = $this->createForm(new VoteType(), $vote, array('method' => 'PUT'));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->voteChecker->checkIfCitizenForumIsInVotePeriod($citizenForum);
            $this->flush($vote);

            return true;
        }

        return View::create($form, 400);
    }

    /**
     * @param CitizenForum         $citizenForum
     * @param ProposalAnswer       $proposalAnswer
     * @param User                 $user
     *
     * @return bool
     */
    public function deleteVote(
        CitizenForum   $citizenForum,
        ProposalAnswer $proposalAnswer,
        User $user
    ) {
        $vote = $this->getUserVote($citizenForum, $user);
        $this->checkConsistency($citizenForum, $proposalAnswer, $vote);
        $this->voteChecker->checkIfCitizenFOrumIsInVotePeriod($citizenForum);
        $this->remove($vote);

        return true;
    }

    /**
     * @param CitizenForum $citizenForum
     * @param Comment      $comment
     *
     * @return Comment $comment
     */
    public function likeComment(CitizenForum $citizenForum, Comment $comment)
    {
        $this->voteChecker->checkIfCitizenForumIsInVotePeriod($citizenForum);
        $this->checkExistComment($citizenForum, $comment);
        $comment = $this->commentVoteManager->postVote(true, $comment);

        return $comment;
    }

    /**
     * @param CitizenForum $citizenForum
     * @param Comment      $comment
     *
     * @return Comment $comment
     */
    public function unLikeComment(CitizenForum $citizenForum, Comment $comment)
    {
        $this->voteChecker->checkIfCitizenForumIsInVotePeriod($citizenForum);
        $this->checkExistComment($citizenForum, $comment);
        $comment = $this->commentVoteManager->postVote(false, $comment);

        return $comment;
    }

    /**
     * @param CitizenForum $citizenForum
     * @param Comment      $comment
     * @param User         $user
     *
     * @return Comment $comment
     */
    public function deleteLikeComment(CitizenForum $citizenForum, Comment $comment, User $user)
    {
        $this->voteChecker->checkIfCitizenForumIsInVotePeriod($citizenForum);
        $this->checkExistComment($citizenForum, $comment);
        $comment = $this->commentVoteManager->deleteVote(true, $comment, $user);

        return $comment;
    }

    /**
     * @param CitizenForum $citizenForum
     * @param Comment              $comment
     * @param User                 $user
     *
     * @return Comment $comment
     */
    public function deleteUnlikeComment(CitizenForum $citizenForum, Comment $comment, User $user)
    {
        $this->voteChecker->checkIfCitizenForumIsInVotePeriod($citizenForum);
        $this->checkExistComment($citizenForum, $comment);
        $comment = $this->commentVoteManager->deleteVote(false, $comment, $user);

        return $comment;
    }

    /**
     * Check if proposal answer belongs to process participation and if vote belongs to proposalAnswer if vote is defined
     *
     * @param CitizenForum   $citizenForum
     * @param ProposalAnswer $proposalAnswer
     * @param Vote           $vote
     */
    protected function checkConsistency(
        CitizenForum $citizenForum,
        ProposalAnswer $proposalAnswer,
        Vote $vote = null
    ) {
        if (!$citizenForum->getProposalAnswers()->contains($proposalAnswer)) {
            throw new HttpException(Codes::HTTP_BAD_REQUEST, 'Proposal answer not belongs to this citizen forum ');
        }

        if (isset($vote) && !$proposalAnswer->getVotes()->contains($vote)) {
            throw new HttpException(Codes::HTTP_BAD_REQUEST, 'Proposal answer has not got this vote ');
        }
    }

    /**
     * @param CitizenForum $citizenForum
     * @param Comment      $comment
     */
    protected function checkExistComment(CitizenForum $citizenForum, Comment $comment)
    {
        if (!$citizenForum->getComments()->contains($comment)) {
            throw new HttpException(Codes::HTTP_BAD_REQUEST, 'Comment not belongs to this citizen forum');
        }

        return;
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
     * @param CitizenForum $citizenForum
     * @param User         $user
     *
     * @return Vote
     */
    protected function getUserVote(CitizenForum $citizenForum, User $user)
    {
        $vote = $this->em->getRepository('Demofony2AppBundle:Vote')->getVoteByUserInCitizenForum($user->getId(), $citizenForum->getId(), false);

        if (!$vote) {
            throw new BadRequestHttpException('The user does not have a vote');
        }

        return $vote;
    }

    public function getAutomaticState(CitizenForum $cf)
    {
        $now = new \DateTime();

          if ($now > $cf->getPresentationAt() && $now < $cf->getDebateAt()) {
            return ProcessParticipationStateEnum::PRESENTATION;
        }

        if ($now > $cf->getPresentationAt() && $now > $cf->getDebateAt() && $now < $cf->getFinishAt()) {
            return ProcessParticipationStateEnum::DEBATE;
        }

        if ($now > $cf->getPresentationAt() && $now > $cf->getDebateAt() && $now > $cf->getFinishAt()) {
            return ProcessParticipationStateEnum::CLOSED;
        }

        return ProcessParticipationStateEnum::PRESENTATION;
    }
}
