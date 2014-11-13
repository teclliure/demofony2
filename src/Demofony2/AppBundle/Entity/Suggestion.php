<?php

namespace Demofony2\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Demofony2\UserBundle\Entity\User;

/**
 * Suggestion
 *
 * @ORM\Table(name="demofony2_suggestion")
 * @ORM\Entity
 */
class Suggestion extends BaseAbstract
{
    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="theme", type="integer")
     */
    private $theme;

    /**
     * @ORM\ManyToOne(targetEntity="Demofony2\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     **/
    private $author;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param  string     $title
     * @return Suggestion
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
     * Set description
     *
     * @param  string     $description
     * @return Suggestion
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
     * Set theme
     *
     * @param  integer    $theme
     * @return Suggestion
     */
    public function setTheme($theme)
    {
        $this->theme = $theme;

        return $this;
    }

    /**
     * Get theme
     *
     * @return integer
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * Set author
     *
     * @param User $author
     *
     * @return Suggestion
     */
    public function setAuthor(User $author = null)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     * @return User
     */
    public function getAuthor()
    {
        return $this->author;
    }
}
