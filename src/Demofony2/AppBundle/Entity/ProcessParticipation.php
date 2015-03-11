<?php

namespace Demofony2\AppBundle\Entity;

use Demofony2\AppBundle\Enum\ProcessParticipationStateEnum;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;

/**
 * ProcessParticipation
 * @ORM\Table(name="demofony2_process_participation")
 * @ORM\Entity(repositoryClass="Demofony2\AppBundle\Repository\ProcessParticipationRepository")
 * @Gedmo\SoftDeleteable(fieldName="removedAt")
 */
class ProcessParticipation extends ParticipationBaseAbstract
{
    const DRAFT = ProcessParticipationStateEnum::DRAFT;
    const DEBATE = ProcessParticipationStateEnum::DEBATE;
    const PRESENTATION = ProcessParticipationStateEnum::PRESENTATION;
    const CLOSED = ProcessParticipationStateEnum::CLOSED;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     * @Serializer\Groups({"detail"})
     */
    private $presentationAt;

    /**
     * @var \DateTime
     * @ORM\Column( type="datetime")
     * @Serializer\Groups({"detail"})
     */
    private $debateAt;

    /**
     * @ORM\OneToMany(targetEntity="Demofony2\AppBundle\Entity\Image", mappedBy="processParticipation", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"position" = "ASC"})
     * @Serializer\Groups({"detail"})
     **/
    protected $images;

    /**
     * @ORM\OneToMany(targetEntity="Demofony2\AppBundle\Entity\Document", mappedBy="processParticipation", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"position" = "ASC"})
     * @Serializer\Groups({"detail"})
     **/
    protected $documents;

    /**
     * @ORM\ManyToOne(targetEntity="Demofony2\UserBundle\Entity\User", inversedBy="processParticipations")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     **/
    protected $author;

    /**
     * @ORM\ManyToMany(targetEntity="Demofony2\AppBundle\Entity\Category", inversedBy="processParticipations")
     * @ORM\JoinTable(name="demofony2_process_participation_category")
     * @Serializer\Groups({"detail"})
     * _
     **/
    protected $categories;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Demofony2\AppBundle\Entity\Comment", mappedBy="processParticipation", cascade={"persist"})
     **/
    protected $comments;

    /**
     * @ORM\OneToMany(targetEntity="Demofony2\AppBundle\Entity\ProposalAnswer", mappedBy="processParticipation", cascade={"persist"})
     * @ORM\OrderBy({"position" = "ASC"})
     * @Serializer\Groups({"detail"})
     **/
    protected $proposalAnswers;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Set presentationAt
     *
     * @param \DateTime $presentationAt
     *
     * @return ProcessParticipation
     */
    public function setPresentationAt($presentationAt)
    {
        $this->presentationAt = $presentationAt;

        return $this;
    }

    /**
     * Get presentationAt
     * @return \DateTime
     */
    public function getPresentationAt()
    {
        return $this->presentationAt;
    }

    /**
     * Set debateAt
     *
     * @param \DateTime $debateAt
     *
     * @return ProcessParticipation
     */
    public function setDebateAt($debateAt)
    {
        $this->debateAt = $debateAt;

        return $this;
    }

    /**
     * Get debateAt
     * @return \DateTime
     */
    public function getDebateAt()
    {
        return $this->debateAt;
    }

    /**
     * Add Comments
     *
     * @param  Comment                   $comment
     * @return ParticipationBaseAbstract
     */
    public function addComment(Comment $comment)
    {
        $comment->setProcessParticipation($this);
        $this->comments[] = $comment;

        return $this;
    }

    /**
     * Add ProposalAnswers
     *
     * @param  ProposalAnswer            $proposalAnswer
     * @return ProcessParticipation
     */
    public function addProposalAnswer(ProposalAnswer $proposalAnswer)
    {
        $proposalAnswer->setProcessParticipation($this);
        $this->proposalAnswers[] = $proposalAnswer;

        return $this;
    }

    /**
     * @return int
     * @Serializer\VirtualProperty
     * @Serializer\Groups({"list", "detail"})
     */
    public function getState()
    {
        $now = new \DateTime();

        if ($now < $this->getPresentationAt()) {
            return ProcessParticipationStateEnum::DRAFT;
        }

        if ($now > $this->getPresentationAt() && $now < $this->getDebateAt()) {
            return ProcessParticipationStateEnum::PRESENTATION;
        }

        if ($now > $this->getPresentationAt() && $now > $this->getDebateAt() && $now < $this->getFinishAt()) {
            return ProcessParticipationStateEnum::DEBATE;
        }

        if ($now > $this->getPresentationAt() && $now > $this->getDebateAt() && $now > $this->getFinishAt()) {
            return ProcessParticipationStateEnum::CLOSED;
        }

        return ProcessParticipationStateEnum::DRAFT;
    }

    /**
     * @return string
     */
    public function getStateName()
    {
        return ProcessParticipationStateEnum::getTranslations()[$this->getState()];
    }
}
