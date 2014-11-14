<?php

namespace Demofony2\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Comment
 *
 * @ORM\Table(name="demofony2_comment")
 * @ORM\Entity
 * @Gedmo\SoftDeleteable(fieldName="removedAt")
 */
class Comment extends BaseAbstract
{
    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="text")
     */
    private $comment;

    /**
     * @var boolean
     *
     * @ORM\Column(name="revised", type="boolean")
     */
    private $revised;

    /**
     * @var boolean
     *
     * @ORM\Column(name="moderated", type="boolean")
     */
    private $moderated;

    /**
     * @ORM\ManyToOne(targetEntity="Demofony2\UserBundle\Entity\User", inversedBy="comments")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     **/
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity="Demofony2\AppBundle\Entity\ProcessParticipation", inversedBy="comments")
     * @ORM\JoinColumn(name="process_participation_id", referencedColumnName="id")
     **/
    private $processParticipation;

    /**
     * @ORM\ManyToOne(targetEntity="Demofony2\AppBundle\Entity\Proposal", inversedBy="comments")
     * @ORM\JoinColumn(name="propsal_id", referencedColumnName="id")
     **/
    private $proposal;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param  string  $title
     * @return Comment
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set comment
     *
     * @param  string  $comment
     * @return Comment
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
     * Set revised
     *
     * @param  boolean $revised
     * @return Comment
     */
    public function setRevised($revised)
    {
        $this->revised = $revised;

        return $this;
    }

    /**
     * Get revised
     *
     * @return boolean
     */
    public function getRevised()
    {
        return $this->revised;
    }

    /**
     * Set moderated
     *
     * @param  boolean $moderated
     * @return Comment
     */
    public function setModerated($moderated)
    {
        $this->moderated = $moderated;

        return $this;
    }

    /**
     * Get moderated
     *
     * @return boolean
     */
    public function getModerated()
    {
        return $this->moderated;
    }
}
