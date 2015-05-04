<?php

namespace Demofony2\AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * DocumentTransparency.
 *
 * @ORM\Table(name="demofony2_document_transparency")
 * @ORM\Entity(repositoryClass="Demofony2\AppBundle\Repository\DocumentTransparencyRepository")
 * @Gedmo\SoftDeleteable(fieldName="removedAt")
 */
class DocumentTransparency extends BaseAbstract
{
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
     *
     * @var string
     */
    protected $slug;

    /**
     * @var CategoryTransparency
     * @ORM\ManyToOne(targetEntity="Demofony2\AppBundle\Entity\CategoryTransparency", inversedBy="documents")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     **/
    private $category;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     * @Assert\NotBlank()
     */
    private $description;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="Demofony2\AppBundle\Entity\LinkTransparency", mappedBy="document", cascade={"persist"})
     * @ORM\OrderBy({"position" = "ASC"})
     * @Assert\Valid
     */
    private $links;

    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="Demofony2\AppBundle\Entity\LawTransparency", cascade={"persist"})
     * @ORM\JoinTable(name="demofony2_document_transparency_laws",
     *                      joinColumns={@ORM\JoinColumn(name="document_transparency_id", referencedColumnName="id")},
     *                      inverseJoinColumns={@ORM\JoinColumn(name="law_id", referencedColumnName="id")}
     *                      )
     * @Assert\Valid
     */
    private $laws;

    /**
     * @var int
     * @ORM\Column(name="visits", type="integer")
     */
    private $visits;

    public function __construct()
    {
        $this->links = new ArrayCollection();
        $this->laws = new ArrayCollection();
        $this->visits = 0;
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
     */
    public function setName($name)
    {
        $this->name = $name;
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
     * @return DocumentTransparency
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return CategoryTransparency
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param CategoryTransparency $category
     */
    public function setCategory(CategoryTransparency $category)
    {
        $this->category = $category;
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
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * @param ArrayCollection $links
     */
    public function setLinks($links)
    {
        $this->links = $links;
    }

    /**
     * @param LinkTransparency $link
     *
     * @return DocumentTransparency
     */
    public function addLink(LinkTransparency $link)
    {
        $link->setDocument($this);
        $this->links[] = $link;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getLaws()
    {
        return $this->laws;
    }

    /**
     * @param ArrayCollection $laws
     */
    public function setLaws($laws)
    {
        $this->laws = $laws;
    }

    /**
     * @param LawTransparency $law
     *
     * @return DocumentTransparency
     */
    public function addLaw(LawTransparency $law)
    {
        $this->laws[] = $law;

        return $this;
    }

    /**
     * @return DocumentTransparency
     */
    public function addVisit()
    {
        $this->visits++;

        return $this;
    }

    /**
     * @return int
     */
    public function getVisits()
    {
        return $this->visits;
    }

    public function __toString()
    {
        return $this->name;
    }
}
