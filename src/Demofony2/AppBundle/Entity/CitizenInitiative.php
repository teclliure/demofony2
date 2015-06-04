<?php

namespace Demofony2\AppBundle\Entity;

use Demofony2\AppBundle\Enum\CitizenInitiativeStateEnum;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Demofony2\AppBundle\Entity\Traits\GalleryTrait;

/**
 * CitizenInitiative.
 *
 * @ORM\Table(name="demofony2_citizen_initiative")
 * @ORM\Entity(repositoryClass="Demofony2\AppBundle\Repository\CitizenInitiativeRepository")
 * @Gedmo\SoftDeleteable(fieldName="removedAt")
 */
class CitizenInitiative extends BaseAbstract
{
    use GalleryTrait;

    /**
     * @var bool
     * @ORM\Column(name="published", type="boolean")
     */
    protected $published;

    /**
     * @var string
     * @ORM\Column(name="title", type="string", length=255)
     * @Assert\NotBlank()
     */
    protected $title;

    /**
     * @ORM\Column(type="string", length=255, name="slug", nullable=false)
     * @Gedmo\Slug(fields={"title"})
     *
     * @var string
     */
    protected $slug;

    /**
     * @var string
     * @ORM\Column(name="description", type="text")
     * @Assert\NotBlank()
     */
    protected $description;

    /**
     * @var \DateTime
     * @ORM\Column(name="start_at", type="datetime")
     * @Assert\NotBlank()
     */
    protected $startAt;

    /**
     * @var \DateTime
     * @ORM\Column(name="finish_at", type="datetime")
     * @Assert\NotBlank()
     */
    protected $finishAt;

    /**
     * @var string
     * @ORM\Column(name="person", type="string", length=255)
     * @Assert\NotBlank()
     */
    protected $person;

    /**
     * @ORM\OneToMany(targetEntity="Demofony2\AppBundle\Entity\Document", mappedBy="citizenInitiative", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"position" = "ASC"})
     * @Assert\Valid
     **/
    protected $documents;

    public function __construct()
    {
        $this->published = false;
    }

    /**
     * @return bool
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * @param bool $published
     *
     * @return CitizenInitiative
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return CitizenInitiative
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     *
     * @return CitizenInitiative
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return CitizenInitiative
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getStartAt()
    {
        return $this->startAt;
    }

    /**
     * @param \DateTime $startAt
     *
     * @return CitizenInitiative
     */
    public function setStartAt($startAt)
    {
        $this->startAt = $startAt;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getFinishAt()
    {
        return $this->finishAt;
    }

    /**
     * @param \DateTime $finishAt
     *
     * @return CitizenInitiative
     */
    public function setFinishAt($finishAt)
    {
        $this->finishAt = $finishAt;

        return $this;
    }

    /**
     * @return string
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * @param string $person
     *
     * @return CitizenInitiative
     */
    public function setPerson($person)
    {
        $this->person = $person;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getDocuments()
    {
        return $this->documents;
    }

    /**
     * @param mixed $documents
     *
     * @return CitizenInitiative
     */
    public function setDocuments($documents)
    {
        $this->documents = $documents;

        return $this;
    }

    /**
     * Add Documents.
     *
     * @param Document $document
     *
     * @return CitizenInitiative
     */
    public function addDocument(Document $document)
    {
        $document->setCitizenInitiative($this);
        $this->documents[] = $document;

        return $this;
    }

    public function getState()
    {
        $now = new \DateTime();

        if ($now < $this->getStartAt()) {
            return CitizenInitiativeStateEnum::DRAFT;
        }

        if ($now > $this->getStartAt() && $now < $this->getFinishAt()) {
            return CitizenInitiativeStateEnum::OPEN;
        }
        if ($now > $this->getStartAt() && $now > $this->getFinishAt()) {
            return CitizenInitiativeStateEnum::CLOSED;
        }

        return CitizenInitiativeStateEnum::DRAFT;
    }
}
