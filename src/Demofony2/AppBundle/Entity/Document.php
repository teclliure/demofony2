<?php

/**
 * Demofony2.
 *
 * @author: Marc Morales Valldepérez <marcmorales83@gmail.com>
 *
 * Date: 13/11/14
 * Time: 17:37
 */
namespace Demofony2\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Demofony2\AppBundle\Entity\Traits\DocumentTrait;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;

/**
 * Document.
 *
 * @ORM\Table(name="demofony2_document")
 * @ORM\Entity
 * @Vich\Uploadable
 * @Gedmo\SoftDeleteable(fieldName="removedAt")
 */
class Document extends BaseAbstract
{
    use DocumentTrait;

    /**
     * @Assert\File(
     *     maxSize="500k",
     *     mimeTypes = {"application/pdf", "application/x-pdf"},
     *     mimeTypesMessage = "constraint.mime_type"
     * )
     * @Vich\UploadableField(
     *     mapping="participation_document",
     *     fileNameProperty="documentName"
     * )
     *
     * @var File
     */
    protected $document;

    /**
     * @var string
     * @ORM\Column(name="name", type="string")
     */
    protected $name;

    /**
     * @var string
     * @Serializer\Groups({"detail"})
     */
    protected $url;

    /**
     * @var int
     * @ORM\Column(name="position", type="integer")
     */
    protected $position = 1;

    /**
     * @var ProcessParticipation
     * @ORM\ManyToOne(targetEntity="Demofony2\AppBundle\Entity\ProcessParticipation", inversedBy="documents")
     * @ORM\JoinColumn(name="process_participation_id", referencedColumnName="id")
     **/
    private $processParticipation;

    /**
     * @var Proposal
     * @ORM\ManyToOne(targetEntity="Demofony2\AppBundle\Entity\Proposal", inversedBy="documents")
     * @ORM\JoinColumn(name="proposal_id", referencedColumnName="id", onDelete="CASCADE")
     **/
    private $proposal;

    /**
     * @var CitizenInitiative
     * @ORM\ManyToOne(targetEntity="Demofony2\AppBundle\Entity\CitizenInitiative", inversedBy="documents", cascade={"persist"})
     * @ORM\JoinColumn(name="citizen_initiative_id", referencedColumnName="id", onDelete="CASCADE")
     **/
    private $citizenInitiative;

    /**
     * @var CitizenForum
     * @ORM\ManyToOne(targetEntity="Demofony2\AppBundle\Entity\CitizenForum", inversedBy="documents")
     * @ORM\JoinColumn(name="citizen_forum_id", referencedColumnName="id", onDelete="CASCADE")
     **/
    private $citizenForum;

    /**
     * @param $url
     *
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param $position
     *
     * @return Document
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
     * @return Document
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
     * @return Document
     */
    public function setProposal(Proposal $proposal)
    {
        $this->proposal = $proposal;

        return $this;
    }

    /**
     * @return CitizenInitiative
     */
    public function getCitizenInitiative()
    {
        return $this->citizenInitiative;
    }

    /**
     * @param CitizenInitiative $citizenInitiative
     *
     * @return Document
     */
    public function setCitizenInitiative(CitizenInitiative $citizenInitiative)
    {
        $this->citizenInitiative = $citizenInitiative;

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
     * @return Document
     */
    public function setCitizenForum(CitizenForum $citizenForum)
    {
        $this->citizenForum = $citizenForum;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     *
     * @return Document
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
}
