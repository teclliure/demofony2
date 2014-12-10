<?php

namespace Demofony2\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Demofony2\UserBundle\Entity\User;
use Gedmo\Mapping\Annotation as Gedmo;
use \Exception;

/**
 * Comment Vote
 *
 * @ORM\Table(name="demofony2_comment_vote")
 * @ORM\Entity(repositoryClass="Demofony2\AppBundle\Repository\CommentVoteRepository")
 * @Gedmo\SoftDeleteable(fieldName="removedAt")
 */
class CommentVote extends BaseAbstract implements UserAwareInterface
{
    /**
     * @ORM\ManyToOne(targetEntity="Demofony2\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     **/
    private $author;

    /**
     * @ORM\Column( type="boolean")
     */
    private $value;

    public function __construct($value)
    {
        if (!is_bool($value)) {
            throw new Exception('value must be true or false');
        }
        $this->value = (boolean) $value;
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
}
