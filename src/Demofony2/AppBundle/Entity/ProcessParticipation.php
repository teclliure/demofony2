<?php

namespace Demofony2\AppBundle\Entity;

use Demofony2\AppBundle\Enum\ProcessParticipationStateEnum;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * ProcessParticipation
 * @ORM\Table(name="demofony2_process_participation")
 * @ORM\Entity(repositoryClass="Demofony2\AppBundle\Repository\ProcessParticipationRepository")
 * @Gedmo\SoftDeleteable(fieldName="removedAt")
 */
class ProcessParticipation extends ParticipationBaseAbstract
{
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
     **/
    protected $images;

    /**
     * @ORM\OneToMany(targetEntity="Demofony2\AppBundle\Entity\Document", mappedBy="processParticipation", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"position" = "ASC"})
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
     * @var bool
     * @ORM\Column(name="published", type="boolean")
     */
    protected $published;

    /**
     * @var bool
     * @ORM\Column(name="state", type="integer")
     * @Serializer\Groups({"detail"})
     */
    protected $state;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->published = false;
        $this->automaticState = true;
        $this->state = ProcessParticipationStateEnum::PRESENTATION;
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
     * @param  ProposalAnswer       $proposalAnswer
     * @return ProcessParticipation
     */
    public function addProposalAnswer(ProposalAnswer $proposalAnswer)
    {
        $proposalAnswer->setProcessParticipation($this);
        $this->proposalAnswers[] = $proposalAnswer;

        return $this;
    }

    /**
     * @return string
     */
    public function getStateName()
    {
        return ProcessParticipationStateEnum::getTranslations()[$this->getState()];
    }

    /**
     * Add Documents
     *
     * @param  Document                  $document
     * @return ParticipationBaseAbstract
     */
    public function addDocument(Document $document)
    {
        $document->setProcessParticipation($this);
        $this->documents[] = $document;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * @param boolean $published
     *
     * @return ProcessParticipation
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param boolean $state
     *
     * @return ProcessParticipation
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @Assert\True(message = "constraint.dates_correlative")
     */
    public function isDatesValid()
    {
        return ($this->presentationAt < $this->debateAt && $this->debateAt < $this->getFinishAt()) ? true : false;
    }
}
