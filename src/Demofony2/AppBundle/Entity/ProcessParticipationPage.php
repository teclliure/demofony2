<?php

namespace Demofony2\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ProcessParticipationPage
 * @ORM\Table(name="demofony2_process_participation_page")
 * @ORM\Entity(repositoryClass="Demofony2\AppBundle\Repository\ProcessParticipationPageRepository")
 * @Gedmo\SoftDeleteable(fieldName="removedAt")
 */
class ProcessParticipationPage extends BaseAbstract
{
    /**
     * @var string
     * @ORM\Column(name="title", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, name="slug", nullable=false)
     * @Gedmo\Slug(fields={"title"})
     * @var string
     */
    protected $slug;

    /**
     * @var string
     * @ORM\Column(name="description", type="text")
     * @Assert\NotBlank()
     */
    private $description;

    /**
     * @var integer
     * @ORM\Column(name="position", type="integer")
     */
    protected $position = 1;

    /**
     * @var bool
     * @ORM\Column(name="published", type="boolean")
     */
    protected $published;

    /**
     * @var ProcessParticipation
     * @ORM\ManyToOne(targetEntity="Demofony2\AppBundle\Entity\ProcessParticipation", inversedBy="pages")
     * @ORM\JoinColumn(name="process_participation_id", referencedColumnName="id")
     **/
    private $processParticipation;

    /**
     * @var ProcessParticipation
     * @ORM\ManyToOne(targetEntity="Demofony2\AppBundle\Entity\CitizenForum", inversedBy="pages")
     * @ORM\JoinColumn(name="citizen_forum_id", referencedColumnName="id")
     **/
    private $citizenForum;

    public function __construct()
    {
        $this->published = false;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Page
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Page
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
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
     * @return ProcessParticipationPage
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
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
     * @return ProcessParticipationPage
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isPublished()
    {
        return $this->published;
    }

    /**
     * @param boolean $published
     *
     * @return ProcessParticipationPage
     */
    public function setPublished($published)
    {
        $this->published = $published;

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
     * @return ProcessParticipationPage
     */
    public function setProcessParticipation($processParticipation)
    {
        $this->processParticipation = $processParticipation;

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
     * @return ProcessParticipationPage
     */
    public function setCitizenForum(CitizenForum $citizenForum)
    {
        $this->citizenForum = $citizenForum;

        return $this;
    }
}
