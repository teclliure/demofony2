<?php

namespace Demofony2\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Demofony2\UserBundle\Entity\User;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;
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
     * @ORM\Column( type="integer")
     */
    private $value;

    public function __construct($value)
    {
        if ($value !== 1 || -1 !== $value) {
            throw new Exception('value must be 1 or -1');
        }
        $this->value = (int)$value;
    }

    /**
     * Set author
     *
     * @param  User $author
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
