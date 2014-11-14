<?php

namespace Demofony2\AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Demofony2\AppBundle\Entity\Proposal;
use Demofony2\AppBundle\Entity\ProcessParticipation;

/**
 * Category
 *
 * @ORM\Table(name="demofony2_category")
 * @ORM\Entity
 */
class Category extends BaseAbstract
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Demofony2\AppBundle\Entity\Proposal", mappedBy="categories")
     **/
    private $proposals;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Demofony2\AppBundle\Entity\ProcessParticipation", mappedBy="categories")
     **/
    private $processParticipations;

    public function __construct()
    {
        $this->proposals = new ArrayCollection();
        $this->processParticipations = new ArrayCollection();
    }
    /**
     * Set name
     *
     * @param string $name
     * @return Category
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Category
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
     * Add proposals
     *
     * @param Proposal $proposals
     * @return Category
     */
    public function addProposal(Proposal $proposals)
    {
        $this->proposals[] = $proposals;

        return $this;
    }

    /**
     * Remove proposals
     *
     * @param Proposal $proposals
     */
    public function removeProposal(Proposal $proposals)
    {
        $this->proposals->removeElement($proposals);
    }

    /**
     * Get proposals
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProposals()
    {
        return $this->proposals;
    }

    /**
     * Add processParticipations
     *
     * @param ProcessParticipation $processParticipations
     * @return Category
     */
    public function addProcessParticipation(ProcessParticipation $processParticipations)
    {
        $this->processParticipations[] = $processParticipations;

        return $this;
    }

    /**
     * Remove processParticipations
     *
     * @param ProcessParticipation $processParticipations
     */
    public function removeProcessParticipation(ProcessParticipation $processParticipations)
    {
        $this->processParticipations->removeElement($processParticipations);
    }

    /**
     * Get processParticipations
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProcessParticipations()
    {
        return $this->processParticipations;
    }
}
