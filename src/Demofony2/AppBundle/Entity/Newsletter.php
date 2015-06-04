<?php

/**
 * Demofony2.
 *
 * @author: Marc Morales Valldepérez <marcmorales83@gmail.com>
 *
 * Date: 16/03/15
 * Time: 15:26
 */
namespace Demofony2\AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Comment.
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
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $sended;

    /**
     * @var datetime
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

    public function __construct()
    {
        $this->proposals = new ArrayCollection();
        $this->documents = new ArrayCollection();
        $this->processParticipations = new ArrayCollection();
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
     * @return datetime
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
}
