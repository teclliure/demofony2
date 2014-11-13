<?php

namespace Demofony2\AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * ParticipationBaseAbstract
 */
class ParticipationBaseAbstract extends BaseAbstract
{
    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    protected $title;

    /**
     * @var integer
     *
     * @ORM\Column(name="state", type="integer")
     */
    protected $state;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    protected $commentsModerated;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    protected $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $finishAt;

    protected function __construct()
    {
        $this->images = new ArrayCollection();
    }

    /**
     * Set title
     *
     * @param string $title
     * @return ParticipationBaseAbstract
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set state
     *
     * @param integer $state
     * @return ParticipationBaseAbstract
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return integer 
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set commentsModerated
     *
     * @param boolean $commentsModerated
     * @return ParticipationBaseAbstract
     */
    public function setCommentsModerated($commentsModerated)
    {
        $this->commentsModerated = $commentsModerated;

        return $this;
    }

    /**
     * Get commentsModerated
     *
     * @return boolean 
     */
    public function getCommentsModerated()
    {
        return $this->commentsModerated;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return ParticipationBaseAbstract
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
     * Set finishAt
     *
     * @param \DateTime $finishAt
     * @return ParticipationBaseAbstract
     */
    public function setFinishAt($finishAt)
    {
        $this->finishAt = $finishAt;

        return $this;
    }

    /**
     * Get finishAt
     *
     * @return \DateTime 
     */
    public function getFinishAt()
    {
        return $this->finishAt;
    }

    /**
     * Add Images
     *
     * @param   Image $image
     * @return ParticipationBaseAbstract
     */
    public function addImage(Image $image)
    {
        $this->images[] = $image;

        return $this;
    }

    /**
     * Remove Images
     *
     * @param Image $image
     */
    public function removeImage(Image $image)
    {
        $this->images->removeElement($image);
    }

    /**
     * Get Images
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getImages()
    {
        return $this->images;
    }
}
