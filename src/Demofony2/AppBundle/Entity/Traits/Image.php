<?php

namespace Demofony2\AppBundle\Entity\Traits;

trait Image
{
    /**
     * @ORM\Column(type="string", length=255, name="image_name", nullable=true)
     */
    protected $imageName;

    protected $removeImage;

    /**
     * Set image_name
     *
     * @param string $imageName
     */
    public function setImageName($imageName)
    {
        $this->imageName = $imageName;
    }

    /**
     * Get image_name
     *
     * @return string
     */
    public function getImageName()
    {
        return $this->imageName;
    }

    public function setImage($file)
    {
        $this->image = $file;
        $this->updatedAt = new \DateTime();

    }

    public function getImage()
    {
        return $this->image;
    }

    public function getRemoveImage()
    {
        return $this->removeImage;
    }

    public function setRemoveImage($removeImage)
    {
        $this->removeImage = $removeImage;
    }
}
