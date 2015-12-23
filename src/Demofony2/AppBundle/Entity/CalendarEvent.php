<?php

namespace Demofony2\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Demofony2\AppBundle\Entity\Traits\ImageCropTrait;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Demofony2\AppBundle\Repository\CalendarEventRepository")
 * @ORM\Table(name="demofony2_calendar_event")
 */
class CalendarEvent
{
    use ImageCropTrait;

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

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
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Demofony2\AppBundle\Entity\CalendarSubevent", mappedBy="event", cascade={"persist", "remove"}, orphanRemoval=true)
     * @Assert\Valid
     **/
    protected $subevents;

    /**
     * @ORM\Column(type="string", length=10, name="color", nullable=false)
     *
     * @var string
     */
    protected $color;

    /**
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param string $color
     */
    public function setColor($color)
    {
        $this->color = $color;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
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
     */
    public function setTitle($title)
    {
        $this->title = $title;
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
     */
    public function setPublished($published)
    {
        $this->published = $published;
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
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
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
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return ArrayCollection
     */
    public function getSubevents()
    {
        return $this->subevents;
    }

    /**
     * @param ArrayCollection $subevents
     */
    public function setSubevents($subevents)
    {
        $this->subevents = $subevents;
    }

    public function addSubevent(CalendarSubevent $subevent)
    {
        $subevent->setEvent($this);
        $this->subevents[] = $subevent;

        return $this;
    }
}
