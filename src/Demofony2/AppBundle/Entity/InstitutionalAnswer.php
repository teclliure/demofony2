<?php

namespace Demofony2\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Demofony2\UserBundle\Entity\User;

/**
 * InstitutionalAnswer.
 *
 * @ORM\Table(name="demofony2_institutional_answer")
 * @ORM\Entity
 * @Gedmo\SoftDeleteable(fieldName="removedAt")
 */
class InstitutionalAnswer extends BaseAbstract implements UserAwareInterface
{
    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="Demofony2\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     **/
    protected $author;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->documents = new ArrayCollection();
    }

    /**
     * Set title.
     *
     * @param string $title
     *
     * @return InstitutionalAnswer
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description.
     *
     * @param string $description
     *
     * @return InstitutionalAnswer
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set author.
     *
     * @param User $author
     *
     * @return InstitutionalAnswer
     */
    public function setAuthor(User $author = null)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author.
     *
     * @return User
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getTitle() ?: '--';
    }
}
