<?php

namespace Demofony2\AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Newsletter entity class
 *
 * @ORM\Table(name="demofony2_newsletter")
 * @ORM\Entity()
 * @Gedmo\SoftDeleteable(fieldName="removedAt")
 */
class Newsletter extends BaseAbstract
{
    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $subject;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $sended;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $sendedAt;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Demofony2\AppBundle\Entity\ProcessParticipation")
     * @ORM\JoinTable(name="demofony2_newsletter_process_participation",
     *      joinColumns={@ORM\JoinColumn(name="newsletter_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="process_participation_id", referencedColumnName="id")}
     *      )
     **/
    protected $processParticipations;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Demofony2\AppBundle\Entity\Proposal")
     * @ORM\JoinTable(name="demofony2_newsletter_proposal",
     *      joinColumns={@ORM\JoinColumn(name="newsletter_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="proposal_id", referencedColumnName="id")}
     *      )
     **/
    protected $proposals;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Demofony2\AppBundle\Entity\DocumentTransparency")
     * @ORM\JoinTable(name="demofony2_newsletter_document_transparency",
     *      joinColumns={@ORM\JoinColumn(name="newsletter_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="document_transparency_id", referencedColumnName="id")}
     *      )
     **/
    protected $documents;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Demofony2\AppBundle\Entity\CitizenForum")
     * @ORM\JoinTable(name="demofony2_newsletter_citizen_forum",
     *      joinColumns={@ORM\JoinColumn(name="newsletter_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="citizen_forum_id", referencedColumnName="id")}
     *      )
     **/
    protected $citizenForums;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Demofony2\AppBundle\Entity\CitizenInitiative")
     * @ORM\JoinTable(name="demofony2_newsletter_citizen_initiatives",
     *      joinColumns={@ORM\JoinColumn(name="newsletter_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="citizen_initiatives_id", referencedColumnName="id")}
     *      )
     **/
    protected $citizenInitiatives;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->proposals = new ArrayCollection();
        $this->documents = new ArrayCollection();
        $this->processParticipations = new ArrayCollection();
        $this->citizenForums = new ArrayCollection();
        $this->citizenInitiatives = new ArrayCollection();
        $this->sended = false;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     *
     * @return Newsletter
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get Description
     *
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set Description
     *
     * @param mixed $description description
     *
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return bool
     */
    public function getSended()
    {
        return $this->sended;
    }

    /**
     * @param bool $sended
     *
     * @return Newsletter
     */
    public function setSended($sended)
    {
        $this->sended = $sended;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getSendedAt()
    {
        return $this->sendedAt;
    }

    /**
     * @param \Datetime $sendedAt
     *
     * @return Newsletter
     */
    public function setSendedAt($sendedAt)
    {
        $this->sendedAt = $sendedAt;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getProcessParticipations()
    {
        return $this->processParticipations;
    }

    /**
     * Add processParticipations.
     *
     * @param ProcessParticipation $processParticipations
     *
     * @return Newsletter
     */
    public function addProcessParticipation(ProcessParticipation $processParticipations)
    {
        $this->processParticipations[] = $processParticipations;

        return $this;
    }

    /**
     * Remove processParticipations.
     *
     * @param ProcessParticipation $processParticipations
     */
    public function removeProcessParticipation(ProcessParticipation $processParticipations)
    {
        $this->processParticipations->removeElement($processParticipations);
    }

    /**
     * @return ArrayCollection
     */
    public function getProposals()
    {
        return $this->proposals;
    }

    /**
     * Add proposals.
     *
     * @param Proposal $proposal
     *
     * @return Newsletter
     */
    public function addProposal(Proposal $proposal)
    {
        $this->proposals[] = $proposal;

        return $this;
    }

    /**
     * Remove proposals.
     *
     * @param Proposal $proposal
     */
    public function removeProposal(Proposal $proposal)
    {
        $this->proposals->removeElement($proposal);
    }

    /**
     * @return ArrayCollection
     */
    public function getDocuments()
    {
        return $this->documents;
    }

    /**
     * Add document.
     *
     * @param DocumentTransparency $document
     *
     * @return Newsletter
     */
    public function addDocument(DocumentTransparency $document)
    {
        $this->documents[] = $document;

        return $this;
    }

    /**
     * Remove document.
     *
     * @param DocumentTransparency $document
     */
    public function removeDocument(DocumentTransparency $document)
    {
        $this->documents->removeElement($document);
    }

    /**
     * @return ArrayCollection
     */
    public function getCitizenForums()
    {
        return $this->citizenForums;
    }

    /**
     * Add citizen forum.
     *
     * @param CitizenForum $citizenForum
     *
     * @return Newsletter
     */
    public function addCitizenForum(CitizenForum $citizenForum)
    {
        $this->citizenForums[] = $citizenForum;

        return $this;
    }

    /**
     * Remove citizenForum.
     *
     * @param CitizenForum $citizenForum
     */
    public function removeCitizenForum(CitizenForum $citizenForum)
    {
        $this->citizenForums->removeElement($citizenForum);
    }

    /**
     * @return ArrayCollection
     */
    public function getCitizenInitiatives()
    {
        return $this->citizenInitiatives;
    }

    /**
     * Add citizen initiative.
     *
     * @param CitizenInitiative $citizenInitiative
     *
     * @return Newsletter
     */
    public function addCitizenInitiative(CitizenInitiative $citizenInitiative)
    {
        $this->citizenInitiatives[] = $citizenInitiative;

        return $this;
    }

    /**
     * Remove citizenInitiative.
     *
     * @param CitizenInitiative $citizenInitiative
     */
    public function removeCitizenInitiative(CitizenInitiative $citizenInitiative)
    {
        $this->citizenInitiatives->removeElement($citizenInitiative);
    }
    public function __toString()
    {
        return $this->subject ? $this->subject : '---';
    }
}
