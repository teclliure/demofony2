<?php

/**
 * Demofony2 for Symfony2.
 *
 * @author: Marc Morales Valldepérez <marcmorales83@gmail.com>
 *
 * Date: 19/03/15
 * Time: 11:32
 */
namespace Demofony2\AppBundle\Entity\Traits;

trait GalleryTrait
{
    /**
     * @ORM\Column(type="array", nullable=true)
     */
    protected $gallery;

    /**
     * @return mixed
     */
    public function getGallery()
    {
        return $this->gallery;
    }

    /**
     * @param mixed $gallery
     */
    public function setGallery($gallery)
    {
        $this->gallery = $gallery;
    }

    public function addImage($image)
    {
        if (!in_array($image, $this->gallery, true)) {
            $this->gallery[] = $image;
        }

        return $this;
    }

    public function getUploadRootDir()
    {
        // absolute path to your directory where images must be saved
        return __DIR__.'/../../../../../web/'.$this->getUploadDir();
    }

    public function getUploadDir()
    {
        return 'uploads/images/participation';
    }

    public function getRealPath($image)
    {
        return null === $image ? null : '/'.$this->getUploadDir().'/gallery/'.$image;
    }

    public function getAbsolutePath()
    {
        return null === $this->image ? null : $this->getUploadRootDir().'/'.$this->image;
    }

    public function getWebPath($image)
    {
        return null === $image ? null : '/'.$this->getUploadDir().'/'.$image;
    }
}
