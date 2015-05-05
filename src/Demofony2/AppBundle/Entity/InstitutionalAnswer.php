<?php

namespace Demofony2\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Demofony2\UserBundle\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @ORM\ManyToMany(targetEntity="Demofony2\AppBundle\Entity\Document", cascade={"persist"})
     * @ORM\JoinTable(name="demofony2_institutional_answer_documents",
     *      joinColumns={@ORM\JoinColumn(name="institutional_answer_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="document_id", referencedColumnName="id", unique=true)}
     *      )
     * @Assert\Valid
     **/
    protected $documents;

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
     * Add documents.
     *
     * @param Document $documents
     *
     * @return InstitutionalAnswer
     */
    public function addDocument(Document $documents)
    {
        $this->documents[] = $documents;

        return $this;
    }

    /**
     * Remove documents.
     *
     * @param Document $documents
     */
    public function removeDocument(Document $documents)
    {
        $this->documents->removeElement($documents);
    }

    /**
     * Get documents.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDocuments()
    {
        return $this->documents;
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
