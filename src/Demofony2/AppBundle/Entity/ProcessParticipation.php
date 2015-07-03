<?php

namespace Demofony2\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ProcessParticipation.
 *
 * @ORM\Table(name="demofony2_process_participation")
 * @ORM\Entity(repositoryClass="Demofony2\AppBundle\Repository\ProcessParticipationRepository")
 * @Gedmo\SoftDeleteable(fieldName="removedAt")
 */
class ProcessParticipation extends ProcessParticipationBase
{
    /**
     * @ORM\OneToMany(targetEntity="Demofony2\AppBundle\Entity\Document", mappedBy="processParticipation", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"position" = "ASC"})
     * @Assert\Valid
     **/
    protected $documents;

    /**
     * @ORM\ManyToMany(targetEntity="Demofony2\AppBundle\Entity\Category", inversedBy="processParticipations")
     * @ORM\JoinTable(name="demofony2_process_participation_category")
     * @Serializer\Groups({"detail"})
     * _
     **/
    protected $categories;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="Demofony2\AppBundle\Entity\Comment", mappedBy="processParticipation", cascade={"persist", "remove"}, orphanRemoval=true)
     **/
    protected $comments;

    /**
     * @ORM\OneToMany(targetEntity="Demofony2\AppBundle\Entity\ProcessParticipationPage", mappedBy="processParticipation", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"position" = "ASC"})
     * @Assert\Valid
     **/
    protected $pages;

    /**
     * @ORM\OneToMany(targetEntity="Demofony2\AppBundle\Entity\Document", mappedBy="processParticipationInstitutionalDocument", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"position" = "ASC"})
     * @Assert\Valid
     **/
    protected $institutionalDocuments;

    public function __construct()
    {
        parent::__construct();
        $this->institutionalDocuments = new ArrayCollection();
    }

    /**
     * Add ProposalAnswers.
     *
     * @param ProposalAnswer $proposalAnswer
     *
     * @return ProcessParticipation
     */
    public function addProposalAnswer(ProposalAnswer $proposalAnswer)
    {
        $proposalAnswer->setProcessParticipation($this);
        $this->proposalAnswers[] = $proposalAnswer;

        return $this;
    }

    /**
     * Add Pages.
     *
     * @param ProcessParticipationPage $page
     *
     * @return ProcessParticipation
     */
    public function addPage(ProcessParticipationPage $page)
    {
        $page->setProcessParticipation($this);
        $this->pages[] = $page;

        return $this;
    }

    /**
     * Add comments.
     *
     * @param Comment $comment
     *
     * @return ProcessParticipation
     */
    public function addComment(Comment $comment)
    {
        $comment->setProcessParticipation($this);
        $this->comments[] = $comment;

        return $this;
    }

    /**
     * @param Document $document
     *
     * @return ProcessParticipation
     */
    public function addDocument(Document $document)
    {
        $document->setProcessParticipation($this);
        $this->documents[] = $document;

        return $this;
    }

    /**
     * @param Document $document Document
     *
     * @return ProcessParticipation
     */
    public function addInstitutionalDocument(Document $document)
    {
        $document->setProcessParticipationInstitutionalDocument($this);
        $this->institutionalDocuments[] = $document;

        return $this;
    }
}
