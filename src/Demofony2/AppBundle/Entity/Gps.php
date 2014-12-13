<?php

namespace Demofony2\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * Gps
 *
 * @ORM\Table(name="demofony2_gps")
 * @ORM\Entity
 */
class Gps
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="lat", type="string", length=255)
     * @Serializer\Groups({"detail"})
     */
    private $lat;

    /**
     * @var string
     *
     * @ORM\Column(name="lng", type="string", length=255)
     * @Serializer\Groups({"detail"})
     */
    private $lng;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set lat
     *
     * @param  string $lat
     * @return Poi
     */
    public function setLat($lat)
    {
        $this->lat = round((float) $lat, 6);

        return $this;
    }

    /**
     * Get lat
     *
     * @return string
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Set lng
     *
     * @param  string $lng
     * @return Poi
     */
    public function setLng($lng)
    {
        $this->lng = round((float) $lng, 6);

        return $this;
    }

    /**
     * Get lng
     *
     * @return string
     */
    public function getLng()
    {
        return $this->lng;
    }
}
