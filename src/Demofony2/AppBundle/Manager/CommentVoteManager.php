<?php

namespace Demofony2\AppBundle\Manager;


use Demofony2\AppBundle\Entity\Comment;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use FOS\RestBundle\Util\Codes;
use Demofony2\AppBundle\Entity\CommentVote;
use Demofony2\UserBundle\Entity\User;

class CommentVoteManager extends AbstractManager
{
    /**
     * @param ObjectManager                $em
     * @param ValidatorInterface           $validator
     */
    public function __construct(ObjectManager $em, ValidatorInterface $validator)
    {
        parent::__construct($em, $validator);
    }

    /**
     * @return \Demofony2\AppBundle\Repository\CommentVoteRepository
     */
    public function getRepository()
    {
        return $this->em->getRepository('Demofony2AppBundle:CommentVote');
    }

    public function create()
    {

    }

    /**
     * @param boolean        $value
     * @param Comment $comment
     *
     * @return Comment
     */
    public function postVote($value, Comment $comment)
    {
        $vote= new CommentVote($value, $comment);
        $this->persist($vote, false);
        $this->flush($vote);
        $this->em->refresh($comment);

        return $comment;
    }

    /**
     * @param boolean        $value
     * @param Comment $comment
     * @param User    $user
     *
     * @return Comment
     */
    public function deleteVote($value, Comment $comment, User $user)
    {
        $vote = $this->em->getRepository('Demofony2AppBundle:CommentVote')->findOneBy(array('comment' => $comment, 'author' => $user, 'value'=> $value));

        if (!$vote && !$value) {
            throw new HttpException(Codes::HTTP_BAD_REQUEST, "User don't unlike this comment");
        }

        if (!$vote && $value) {
            throw new HttpException(Codes::HTTP_BAD_REQUEST, "User don't like this comment");
        }

        $this->remove($vote);
        $this->em->refresh($comment);

        return $comment;
    }
}
