<?php

namespace Demofony2\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;
use Oh\GoogleMapFormTypeBundle\Validator\Constraints as OhAssert;

/**
 * Gps.
 *
 * @ORM\Table(name="demofony2_gps")
 * @ORM\Entity
 */
class Gps
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var float
     *
     * @ORM\Column(name="lat", type="float", precision=14, nullable=true)
     * @Serializer\Groups({"detail"})
     * @Serializer\SerializedName("latitude")
     * @Serializer\Type("float")
     */
    protected $lat = 41.4926867;

    /**
     * @var float
     *
     * @ORM\Column(name="lng", type="float", precision=14, nullable=true)
     * @Serializer\Groups({"detail"})
     * @Serializer\SerializedName("longitude")
     * @Serializer\Type("float")
     */
    protected $lng = 2.3613954;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set lat.
     *
     * @param string $lat
     *
     * @return $this
     */
    public function setLat($lat)
    {
        $this->lat = $lat;

        return $this;
    }

    /**
     * Get lat.
     *
     * @return float
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Set lng.
     *
     * @param string $lng
     *
     * @return $this
     */
    public function setLng($lng)
    {
        $this->lng = $lng;

        return $this;
    }

    /**
     * Get lng.
     *
     * @return float
     */
    public function getLng()
    {
        return $this->lng;
    }

    public function setLatLng($latlng)
    {
        $this->setLat($latlng['lat']);
        $this->setLng($latlng['lng']);

        return $this;
    }

    /**
     * @Assert\NotBlank()
     * @OhAssert\LatLng()
     */
    public function getLatLng()
    {
        return array('lat' => $this->getLat(),'lng' => $this->getLng());
    }
}
