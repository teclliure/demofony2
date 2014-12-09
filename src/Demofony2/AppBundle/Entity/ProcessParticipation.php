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
     * @ORM\ManyToMany(targetEntity="Demofony2\AppBundle\Entity\Image")
     * @ORM\JoinTable(name="demofony2_process_participation_images",
     *      joinColumns={@ORM\JoinColumn(name="process_participation_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="image_id", referencedColumnName="id", unique=true)}
     *      )
     **/
    protected $images;

    /**
     * @ORM\ManyToMany(targetEntity="Demofony2\AppBundle\Entity\Document")
     * @ORM\JoinTable(name="demofony2_process_participation_documents",
     *      joinColumns={@ORM\JoinColumn(name="process_participation_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="document_id", referencedColumnName="id", unique=true)}
     *      )
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
     * @ORM\ManyToMany(targetEntity="Demofony2\AppBundle\Entity\ProposalAnswer", cascade={"persist"})
     * @ORM\JoinTable(name="demofony2_process_participation_proposal_answer",
     *      joinColumns={@ORM\JoinColumn(name="process_participation_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="proposal_answer_id", referencedColumnName="id", unique=true)}
     *      )
     *     * @Serializer\Groups({"detail"})
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
        $this->comments[] = $comment;

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
     * @return int
     * @Serializer\VirtualProperty
     * @Serializer\Groups({"detail"})
     */
    public function getTotalVotesCount()
    {
        $result = 0;

        foreach($this->getProposalAnswers() as $proposalAnswer) {
            $result += $proposalAnswer->getVotes()->count();
        }

        return $result;
    }
}
