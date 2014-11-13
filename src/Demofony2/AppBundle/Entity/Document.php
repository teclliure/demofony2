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
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Demofony2\CoreBundle\Entity\Traits\DocumentTrait;

/**
 * Document
 *
 * @ORM\Table(name="demofony2_document")
 * @ORM\Entity
 * @Vich\Uploadable
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
}
