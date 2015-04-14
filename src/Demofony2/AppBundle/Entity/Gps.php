<?php

namespace Demofony2\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;
use Oh\GoogleMapFormTypeBundle\Validator\Constraints as OhAssert;

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
     * @var float
     *
     * @ORM\Column(name="lat", type="float", precision=14, nullable=true)
     * @Serializer\Groups({"detail"})
     * @Serializer\SerializedName("latitude")
     * @Serializer\Type("float")
     */
    private $lat;

    /**
     * @var float
     *
     * @ORM\Column(name="lng", type="float", precision=14, nullable=true)
     * @Serializer\Groups({"detail"})
     * @Serializer\SerializedName("longitude")
     * @Serializer\Type("float")
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
        //        $this->lat = round((float) $lat, 6);
        $this->lat = $lat;

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
        //        $this->lng = round((float) $lng, 6);
        $this->lng = $lng;

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
