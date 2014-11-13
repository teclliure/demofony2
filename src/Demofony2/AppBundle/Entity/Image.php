<?php
/**
 * Demofony2
 *
 * @author: Marc Morales Valldepérez <marcmorales83@gmail.com>
 *
 * Date: 13/11/14
 * Time: 16:52
 */
namespace Demofony2\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Demofony2\AppBundle\Entity\Traits\ImageTrait;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Image
 *
 * @ORM\Table(name="demofony2_image")
 * @ORM\Entity
 * @Vich\Uploadable
 */
class Image extends BaseAbstract
{
    use ImageTrait;

    /**
     * @Assert\File(
     *     maxSize="500k",
     *     mimeTypes={"image/png", "image/jpeg", "image/pjpeg"},
     *     mimeTypesMessage = "constraint.mime_type"
     * )
     * @Vich\UploadableField(
     *     mapping="participation_image",
     *     fileNameProperty="imageName"
     * )
     * @var File $image
     */
    protected $image;
}
