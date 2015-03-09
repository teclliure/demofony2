<?php
/**
 * Demofony2
 *
 * @author: Marc Morales Valldepérez <marcmorales83@gmail.com>
 *
 * Date: 13/11/14
 * Time: 17:37
 */
namespace Demofony2\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Demofony2\AppBundle\Entity\Traits\DocumentTrait;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;

/**
 * Document
 *
 * @ORM\Table(name="demofony2_document")
 * @ORM\Entity
 * @Vich\Uploadable
 * @Gedmo\SoftDeleteable(fieldName="removedAt")
 */
class Document extends BaseAbstract
{
    use DocumentTrait;

    /**
     * @Assert\File(
     *     maxSize="500k",
     *     mimeTypes = {"application/pdf", "application/x-pdf"},
     *     mimeTypesMessage = "constraint.mime_type"
     * )
     * @Vich\UploadableField(
     *     mapping="participation_document",
     *     fileNameProperty="documentName"
     * )
     * @var File $document
     */
    protected $document;

    /**
     * @var string
     * @Serializer\Groups({"detail"})
     */
    protected $url;

    /**
     * @var integer
     * @ORM\Column(name="position", type="integer")
     */
    protected $position=1;

    /**
     * @param $url
     *
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return integer
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param $position
     *
     * @return Document
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

//    public function __toString()
//    {
//        return 'test';
//    }
}
