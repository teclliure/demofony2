<?php

namespace Demofony2\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Demofony2\UserBundle\Entity\User;
use \Exception;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Comment Vote
 *
 * @ORM\Table(name="demofony2_comment_vote",uniqueConstraints={@ORM\UniqueConstraint(name="search_idx", columns={"user_id", "comment_id"})}))
 * @ORM\Entity(repositoryClass="Demofony2\AppBundle\Repository\CommentVoteRepository")
 * @UniqueEntity(fields={"author", "comment"}, message="This user already vote this comment")
 */
class CommentVote extends BaseAbstract implements UserAwareInterface
{
    /**
     * @ORM\ManyToOne(targetEntity="Demofony2\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     **/
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity="Demofony2\AppBundle\Entity\Comment")
     * @ORM\JoinColumn(name="comment_id", referencedColumnName="id")
     **/
    private $comment;

    /**
     * @ORM\Column( type="boolean")
     */
    private $value;

    public function __construct($value, Comment $comment)
    {
        if (!is_bool($value)) {
            throw new Exception('Value must be true or false');
        }
        $this->value = (boolean) $value;
        $this->comment = $comment;
    }

    /**
     * Set author
     *
     * @param  User        $author
     * @return CommentVote
     */
    public function setAuthor(User $author = null)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return User
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @return bool
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return Comment
     */
    public function getComment()
    {
        return $this->comment;
    }
}
