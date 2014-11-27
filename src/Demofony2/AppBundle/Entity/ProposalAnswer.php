<?php

namespace Demofony2\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * ProposalAnswer
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
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity="Demofony2\AppBundle\Entity\Vote")
     * @ORM\JoinTable(name="demofony2_proposal_answer_vote",
     *      joinColumns={@ORM\JoinColumn(name="proposal_answer_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="vote_id", referencedColumnName="id", unique=true)}
     *      )
     **/
    protected $votes;

    public function __construct()
    {
        $this->votes = new ArrayCollection();
    }

    /**
     * Set title
     *
     * @param  string         $title
     * @return ProposalAnswer
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
     * Set description
     *
     * @param  string         $description
     * @return ProposalAnswer
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Add votes
     *
     * @param  Vote           $votes
     * @return ProposalAnswer
     */
    public function addVote(Vote $votes)
    {
        $this->votes[] = $votes;

        return $this;
    }

    /**
     * Remove votes
     *
     * @param Vote $votes
     */
    public function removeVote(Vote $votes)
    {
        $this->votes->removeElement($votes);
    }

    /**
     * Get votes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVotes()
    {
        return $this->votes;
    }
}
