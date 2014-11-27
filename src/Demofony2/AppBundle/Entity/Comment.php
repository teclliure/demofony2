<?php

namespace Demofony2\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;
use Demofony2\AppBundle\Enum\ProposalStateEnum;
use Demofony2\AppBundle\Enum\ProcessParticipationStateEnum;
use Demofony2\UserBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * Comment
 * @ORM\Table(name="demofony2_comment")
 * @ORM\Entity(repositoryClass="Demofony2\AppBundle\Repository\CommentRepository")
 * @Gedmo\SoftDeleteable(fieldName="removedAt")
 * @Gedmo\Tree(type="nested")
 */
class Comment  extends BaseAbstract  implements UserAwareInterface
{
    /**
     * @var string
     * @ORM\Column(name="title", type="string", length=255)
     * @Assert\NotBlank(groups={"default", "create"})
     * @Serializer\Groups({"list", "children-list"})
     */
    private $title;

    /**
     * @var string
     * @ORM\Column(name="comment", type="text")
     * @Assert\NotBlank(groups={"default", "create"})
     * @Serializer\Groups({"list", "children-list"})
     */
    private $comment;

    /**
     * @var boolean
     * @ORM\Column(name="revised", type="boolean")
     */
    private $revised = false;

    /**
     * @var boolean
     * @ORM\Column(name="moderated", type="boolean")
     */
    private $moderated = false;

    /**
     * @ORM\ManyToOne(targetEntity="Demofony2\UserBundle\Entity\User", inversedBy="comments")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @Serializer\Groups({"list", "children-list"})
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
     * @Gedmo\TreeLeft
     * @ORM\Column(name="lft", type="integer")
     * @Serializer\Groups({ "children-list"})
     */
    private $lft;

    /**
     * @Gedmo\TreeLevel
     * @ORM\Column(name="lvl", type="integer")
     * @Serializer\Groups({ "children-list"})
     */
    private $lvl;

    /**
     * @Gedmo\TreeRight
     * @ORM\Column(name="rgt", type="integer")
     */
    private $rgt;

    /**
     * @Gedmo\TreeRoot
     * @ORM\Column(name="root", type="integer", nullable=true)
     */
    private $root;

    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="Comment", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="parent", fetch="EXTRA_LAZY")
     * @ORM\OrderBy({"lft" = "ASC"})
     */
    private $children;

    /**
     * @var int
     * @Serializer\Groups({"list"})
     */
    protected $childrenCount;

    /**
     * construct
     */
    public function __construct()
    {
        $this->children = new ArrayCollection();
    }

    /**
     * Get id
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Comment
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return Comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set revised
     *
     * @param boolean $revised
     *
     * @return Comment
     */
    public function setRevised($revised)
    {
        $this->revised = $revised;

        return $this;
    }

    /**
     * Get revised
     * @return boolean
     */
    public function getRevised()
    {
        return $this->revised;
    }

    /**
     * Set moderated
     *
     * @param boolean $moderated
     *
     * @return Comment
     */
    public function setModerated($moderated)
    {
        $this->moderated = $moderated;

        return $this;
    }

    /**
     * Get moderated
     * @return boolean
     */
    public function getModerated()
    {
        return $this->moderated;
    }

    /**
     * @param Comment $parent
     *
     * @return Comment
     */
    public function setParent(Comment $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Comment
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set processParticipation
     *
     * @param ProcessParticipation $processParticipation
     *
     * @return Comment
     */
    public function setProcessParticipation(ProcessParticipation $processParticipation)
    {
        $this->processParticipation = $processParticipation;

        return $this;
    }

    /**
     * Get processParticipation
     * @return ProcessParticipation
     */
    public function getProcessParticipation()
    {
        return $this->processParticipation;
    }

    /**
     * Set proposal
     *
     * @param Proposal $proposal
     *
     * @return Comment
     */
    public function setProposal(Proposal $proposal)
    {
        $this->proposal = $proposal;

        return $this;
    }

    /**
     * Get proposal
     * @return Proposal
     */
    public function getProposal()
    {
        return $this->proposal;
    }

    /**
     * Set author
     *
     * @param User $author
     *
     * @return Comment
     */
    public function setAuthor(User $author = null)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     * @return Proposal
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Add children
     *
     * @param Comment $children
     *
     * @return Comment
     */
    public function addChild(Comment $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     *
     * @param Comment $children
     */
    public function removeChild(Comment $children)
    {
        $this->children->removeElement($children);
    }

    /**
     * Get children
     * @return ArrayCollection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set childrenCount
     *
     * @param int $childrenCount
     *
     * @return Comment
     */
    public function setChildrenCount($childrenCount)
    {
        $this->childrenCount = $childrenCount;

        return $this;
    }

    /**
     * Get childrenCount
     * @return int
     */
    public function getChildrenCount()
    {
        return $this->childrenCount;
    }

    public function __toString()
    {
        return $this->getTitle();
    }

    /**
     * @Assert\True(message = "Neither of both, processParticipation or proposal is set")
     */
    public function isParticipationSet()
    {
        return (is_object($this->getProcessParticipation()) && !is_object($this->getProposal())) || (!is_object(
                $this->getProcessParticipation()
            ) && is_object($this->getProposal()));
    }

    /**
     * @Assert\True(message = "Debate is not open")
     */
    public function isDebateOpen()
    {
        if (is_object($p = $this->getProcessParticipation())) {
            return ProcessParticipationStateEnum::DEBATE === $p->getState();
        }

        if (is_object($p = $this->getProposal())) {
            return ProposalStateEnum::DEBATE === $p->getState();
        }

        return false;
    }

    /**
     * @Assert\True(message = "Parent is not consistent")
     */
    public function isParentConsistent()
    {
        $parent = $this->getParent();

        if (!is_object($parent)) {
            return true;
        }

        $pp = $this->getProcessParticipation();

        if (is_object($pp) && is_object($parentProcess = $parent->getProcessParticipation())) {
            return $pp === $parentProcess;
        }

        $pp = $this->getProposal();

        if (is_object($pp) && is_object($parentProposal = $parent->getProposal())) {
            return $pp === $parentProposal;
        }

        return false;
    }
}
