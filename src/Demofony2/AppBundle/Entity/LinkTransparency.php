<?php

namespace Demofony2\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * LinkTransparency.
 *
 * @ORM\Table(name="demofony2_link_transparency")
 * @ORM\Entity
 * @Gedmo\SoftDeleteable(fieldName="removedAt")
 */
class LinkTransparency extends BaseAbstract
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="text")
     * @Assert\NotBlank()
     * @Assert\Url()
     */
    private $url;

    /**
     * @var LawTransparency
     * @ORM\ManyToOne(targetEntity="Demofony2\AppBundle\Entity\LawTransparency", inversedBy="links")
     * @ORM\JoinColumn(name="law_id", referencedColumnName="id")
     **/
    private $law;

    /**
     * @var DocumentTransparency
     * @ORM\ManyToOne(targetEntity="Demofony2\AppBundle\Entity\DocumentTransparency", inversedBy="links")
     * @ORM\JoinColumn(name="document_id", referencedColumnName="id")
     **/
    private $document;
    /**
     * @ORM\Column(name="position", type="integer", nullable=false)
     *
     * @var int
     */
    private $position = 1;

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param $url
     *
     * @return LinkTransparency
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
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
     * @return LinkTransparency
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return LawTransparency
     */
    public function getLaw()
    {
        return $this->law;
    }

    /**
     * @param LawTransparency $law
     *
     * @return LinkTransparency
     */
    public function setLaw(LawTransparency $law)
    {
        $this->law = $law;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDocument()
    {
        return $this->document;
    }

    /**
     * @param mixed $document
     *
     * @return LinkTransparency
     */
    public function setDocument(DocumentTransparency $document)
    {
        $this->document = $document;

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
     * @return LinkTransparency
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }
}
