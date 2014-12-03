<?php

namespace Demofony2\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Demofony2\UserBundle\Entity\User;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;

/**
 * Vote
 *
 * @ORM\Table(name="demofony2_vote")
 * @ORM\Entity(repositoryClass="Demofony2\AppBundle\Repository\VoteRepository")
 * @Gedmo\SoftDeleteable(fieldName="removedAt")
 */
class Vote extends BaseAbstract implements UserAwareInterface
{
    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="text")
     * @Serializer\Groups({"detail"})
     */
    private $comment;

    /**
     * @ORM\ManyToOne(targetEntity="Demofony2\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     **/
    private $author;

    /**
     * Set comment
     *
     * @param  string $comment
     * @return Vote
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set author
     *
     * @param  User $author
     * @return Vote
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
