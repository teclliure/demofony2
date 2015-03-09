<?php

namespace Demofony2\AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Demofony2\AppBundle\Entity\Traits\ImageTrait;
use FOS\RestBundle\Controller\Annotations\Link;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;

/**
 * DocumentTransparency
 *
 * @ORM\Table(name="demofony2_document_transparency")
 * @ORM\Entity
 * @Gedmo\SoftDeleteable(fieldName="removedAt")
 */
class DocumentTransparency extends BaseAbstract
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;


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
     */
    private $description;

    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="Demofony2\AppBundle\Entity\LinkTransparency", cascade={"persist"})
     * @ORM\JoinTable(name="demofony2_document_transparency_links",
     *      joinColumns={@ORM\JoinColumn(name="link_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="document_transparency_id", referencedColumnName="id", unique=true)}
     *      )
     */
    private $links;

    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="Demofony2\AppBundle\Entity\LawTransparency", cascade={"persist"})
     * @ORM\JoinTable(name="demofony2_document_transparency_laws",
     *      joinColumns={@ORM\JoinColumn(name="law_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="document_transparency_id", referencedColumnName="id", unique=true)}
     *      )
     */
    private $laws;

    public function __construct()
    {
        $this->links = new ArrayCollection();
        $this->laws = new ArrayCollection();
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
}
