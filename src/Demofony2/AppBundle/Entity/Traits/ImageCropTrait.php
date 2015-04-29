<?php

namespace Demofony2\AppBundle\Entity\Traits;

trait ImageCropTrait
{
    /**
     * @ORM\Column(type="string", length=255, name="image", nullable=true)
     */
    protected $image;

    /**
     * Set image
     *
     * @param string $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    public function getUploadRootDir()
    {
        // absolute path to your directory where images must be saved
        return __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'web' . DIRECTORY_SEPARATOR . $this->getUploadDir();
    }

    public function getUploadDir()
    {
        return 'uploads' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR;
    }

    public function getAbsolutePath()
    {
        return null === $this->image ? null : $this->getUploadRootDir() . DIRECTORY_SEPARATOR . $this->image;
    }

    public function getWebPath()
    {
        return null === $this->image ? null : DIRECTORY_SEPARATOR . $this->getUploadDir() . DIRECTORY_SEPARATOR . $this->image;
    }
}
