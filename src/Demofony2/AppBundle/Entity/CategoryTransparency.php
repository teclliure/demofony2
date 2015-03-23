<?php

namespace Demofony2\AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Demofony2\AppBundle\Entity\Traits\ImageCropTrait;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * CategoryTransparency
 *
 * @ORM\Table(name="demofony2_category_transparency")
 * @ORM\Entity
 * @Gedmo\SoftDeleteable(fieldName="removedAt")
 * @Vich\Uploadable
 */
class CategoryTransparency extends BaseAbstract
{
    use ImageCropTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, name="slug", nullable=false)
     * @Gedmo\Slug(fields={"name"})
     * @var string
     */
    protected $slug;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Demofony2\AppBundle\Entity\DocumentTransparency", mappedBy="category")
     **/
    private $documents;

    /**
     * @var integer
     * @Gedmo\SortablePosition
     * @ORM\Column(name="position", type="integer")
     */
    protected $position;

    public function __construct()
    {
        $this->documents = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return CategoryTransparency
     */
    public function setName($name)
    {
        $this->name = $name;

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
     * @return CategoryTransparency
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

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
     * @param ArrayCollection $documents
     */
    public function setDocuments($documents)
    {
        $this->documents = $documents;
    }

    /**
     * @param DocumentTransparency $document
     *
     * @return CategoryTransparency
     */
    public function addDocument(DocumentTransparency $document)
    {
        $this->documents->add($document);

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
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    public function __toString()
    {
        return $this->getName();
    }
}
