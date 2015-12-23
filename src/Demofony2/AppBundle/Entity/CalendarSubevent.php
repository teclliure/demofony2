<?php

namespace Demofony2\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Demofony2\AppBundle\Entity\Traits\ImageCropTrait;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass="Demofony2\AppBundle\Repository\CalendarSubeventRepository")
 * @ORM\Table(name="demofony2_calendar_subevent")
 */
class CalendarSubevent
{
    use ImageCropTrait;

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

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
     * @ORM\Column(type="string", length=255, name="location", nullable=false)
     *
     * @var string
     */
    protected $location;

    /**
     * @ORM\Column(type="string", length=10, name="color", nullable=false)
     *
     * @var string
     */
    protected $color;

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
     * @var CalendarEvent
     * @ORM\ManyToOne(targetEntity="Demofony2\AppBundle\Entity\CalendarEvent", inversedBy="subevents")
     * @ORM\JoinColumn(name="event_id", referencedColumnName="id")
     **/
    protected $event;

    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context)
    {
        if ($this->startAt->getTimestamp() > $this->finishAt->getTimestamp()) {
            $context->buildViolation('The starting date must be anterior than the ending date !')->atPath('startAt')->addViolation();
        }
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
     * @return mixed
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param mixed $color
     */
    public function setColor($color)
    {
        $this->color = $color;
    }

    /**
     * @return mixed
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param mixed $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
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
     */
    public function setFinishAt($finishAt)
    {
        $this->finishAt = $finishAt;
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
     * @return \DateTime
     */
    public function getStartAt()
    {
        return $this->startAt;
    }

    /**
     * @param \DateTime $startAt
     */
    public function setStartAt($startAt)
    {
        $this->startAt = $startAt;
    }

    /**
     * @return CalendarEvent
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @param CalendarEvent $event
     */
    public function setEvent($event)
    {
        $this->event = $event;
    }
}
