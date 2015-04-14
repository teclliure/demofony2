<?php

namespace Demofony2\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Page
 * @ORM\Table(name="demofony2_page", indexes={@ORM\Index(name="url_idx", columns={"url"})})
 * @ORM\Entity(repositoryClass="Demofony2\AppBundle\Repository\PageRepository")
 * @Gedmo\SoftDeleteable(fieldName="removedAt")
 * @UniqueEntity(fields={"url"}, message="This url already exists")
 */
class Page extends BaseAbstract
{
    /**
     * @var string
     * @ORM\Column(name="title", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $title;

    /**
     * @var string
     * @ORM\Column(name="comment", type="text")
     * @Assert\NotBlank()
     */
    private $description;

    /**
     * @var string
     * @ORM\Column(name="block_content", type="text", nullable=true)
     */
    private $blockContent;

    /**
     * @var string
     * @ORM\Column(name="url", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $url;

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Page
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
     * @param string $description
     *
     * @return Page
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
     * Set blockContent
     *
     * @param string $blockContent
     *
     * @return Page
     */
    public function setBlockContent($blockContent)
    {
        $this->blockContent = $blockContent;

        return $this;
    }

    /**
     * Get blockContent
     *
     * @return string
     */
    public function getBlockContent()
    {
        return $this->blockContent;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return Page
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }
}
