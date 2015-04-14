<?php

namespace Demofony2\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Participation Statistics
 * @ORM\Table(name="demofony2_participation_statistics")
 * @ORM\Entity(repositoryClass="Demofony2\AppBundle\Repository\ParticipationStatisticsRepository")
 * @UniqueEntity("day")
 */
class ParticipationStatistics extends BaseAbstract
{
    /**
     * @var \Datetime
     * @ORM\Column(type="date")
     */
    protected $day;

    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    protected $comments;

    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    protected $votes;

    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    protected $proposals;

    public function __construct()
    {
        $this->day = new \DateTime('TODAY');
        $this->comments = 0;
        $this->votes = 0;
        $this->proposals = 0;
    }

    /**
     * @return \Datetime
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * @return mixed
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     *
     * @return ParticipationStatistics
     */
    public function addComment()
    {
        $this->comments++;

        return $this;
    }

    /**
     * @return int
     */
    public function getVotes()
    {
        return $this->votes;
    }

    /**
     *
     * @return ParticipationStatistics
     */
    public function addVote()
    {
        $this->votes++;

        return $this;
    }

    /**
     * @return int
     */
    public function getProposals()
    {
        return $this->proposals;
    }

    /**
     *
     * @return ParticipationStatistics
     */
    public function addProposal()
    {
        $this->proposals++;

        return $this;
    }
}
