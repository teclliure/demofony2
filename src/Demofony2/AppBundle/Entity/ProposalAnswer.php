<?php

namespace Demofony2\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;
use Demofony2\AppBundle\Enum\IconEnum;

/**
 * ProposalAnswer.
 *
 * @ORM\Table(name="demofony2_proposal_answer")
 * @ORM\Entity
 * @Gedmo\SoftDeleteable(fieldName="removedAt")
 */
class ProposalAnswer extends BaseAbstract
{
    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     * @Serializer\Groups({"detail"})
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     * @Serializer\Groups({"detail"})
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity="Demofony2\AppBundle\Entity\Vote", fetch="EXTRA_LAZY")
     * @ORM\JoinTable(name="demofony2_proposal_answer_vote",
     *      joinColumns={@ORM\JoinColumn(name="proposal_answer_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="vote_id", referencedColumnName="id", unique=true)}
     *      )
     **/
    protected $votes;

    /**
     * @var bool
     * @Serializer\Type("boolean")
     * @Serializer\Groups({"detail"})
     */
    protected $userHasVoteThisProposalAnswer;

    /**
     * @var int
     * @ORM\Column(name="position", type="integer")
     */
    protected $position = 1;

    /**
     * @var ProcessParticipation
     * @ORM\ManyToOne(targetEntity="Demofony2\AppBundle\Entity\ProcessParticipation", inversedBy="proposalAnswers")
     * @ORM\JoinColumn(name="process_participation_id", referencedColumnName="id")
     **/
    private $processParticipation;

    /**
     * @var Proposal
     * @ORM\ManyToOne(targetEntity="Demofony2\AppBundle\Entity\Proposal", inversedBy="proposalAnswers")
     * @ORM\JoinColumn(name="proposal_id", referencedColumnName="id")
     **/
    private $proposal;

    /**
     * @var Proposal
     * @ORM\ManyToOne(targetEntity="Demofony2\AppBundle\Entity\CitizenForum", inversedBy="proposalAnswers")
     * @ORM\JoinColumn(name="citizen_forum_id", referencedColumnName="id")
     **/
    private $citizenForum;

    /**
     * @var int
     * @ORM\Column(name="icon", type="integer", nullable=true)
     */
    protected $icon;

    public function __construct()
    {
        $this->votes = new ArrayCollection();
    }

    /**
     * Set title.
     *
     * @param string $title
     *
     * @return ProposalAnswer
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description.
     *
     * @param string $description
     *
     * @return ProposalAnswer
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Add votes.
     *
     * @param Vote $votes
     *
     * @return ProposalAnswer
     */
    public function addVote(Vote $votes)
    {
        $this->votes[] = $votes;

        return $this;
    }

    /**
     * Remove votes.
     *
     * @param Vote $votes
     */
    public function removeVote(Vote $votes)
    {
        $this->votes->removeElement($votes);
    }

    /**
     * Get votes.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVotes()
    {
        return $this->votes;
    }

    /**
     * Get number votes.
     *
     * @Serializer\VirtualProperty
     * @Serializer\Groups({"list", "detail"})
     *
     * @return int
     */
    public function getVotesCount()
    {
        return $this->votes->count();
    }

    /**
     * Set userHasVoteThisProposalAnswer.
     *
     * @param bool $userHasVoteThisProposalAnswer
     *
     * @return ProposalAnswer
     */
    public function setUserHasVoteThisProposalAnswer($userHasVoteThisProposalAnswer)
    {
        $this->userHasVoteThisProposalAnswer = $userHasVoteThisProposalAnswer;

        return $this;
    }

    /**
     * Get userHasVoteThisProposalAnswer.
     *
     * @return bool
     */
    public function getUserHasVoteThisProposalAnswer()
    {
        return $this->userHasVoteThisProposalAnswer;
    }

    /**
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param int $position
     *
     * @return ProposalAnswer
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * @return ProcessParticipation
     */
    public function getProcessParticipation()
    {
        return $this->processParticipation;
    }

    /**
     * @param ProcessParticipation $processParticipation
     *
     * @return ProposalAnswer
     */
    public function setProcessParticipation(ProcessParticipation $processParticipation)
    {
        $this->processParticipation = $processParticipation;

        return $this;
    }

    /**
     * @return Proposal
     */
    public function getProposal()
    {
        return $this->proposal;
    }

    /**
     * @param Proposal $proposal
     *
     * @return ProposalAnswer
     */
    public function setProposal(Proposal $proposal)
    {
        $this->proposal = $proposal;

        return $this;
    }

    /**
     * @return CitizenForum
     */
    public function getCitizenForum()
    {
        return $this->citizenForum;
    }

    /**
     * @param CitizenForum $citizenForum
     *
     * @return ProposalAnswer
     */
    public function setCitizenForum(CitizenForum $citizenForum)
    {
        $this->citizenForum = $citizenForum;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @param mixed $icon
     *
     * @return CategoryTransparency
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * @return string
     * @Serializer\VirtualProperty
     * @Serializer\Groups({"detail"})
     */
    public function getIconName()
    {
        $icons =  IconEnum::arrayToCss();

        return  array_key_exists($this->icon, $icons) ? 'icon-'.IconEnum::arrayToCss()[$this->icon] : '';
    }
}
