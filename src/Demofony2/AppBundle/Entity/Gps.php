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
     * @var string
     *
     * @ORM\Column(name="lat", type="string", length=255)
     * @Serializer\Groups({"detail"})
     * @Serializer\SerializedName("latitude")
     * @Serializer\Type("float")
     */
    private $lat = '41.4926867';

    /**
     * @var string
     *
     * @ORM\Column(name="lng", type="string", length=255)
     * @Serializer\Groups({"detail"})
     * @Serializer\SerializedName("longitude")
     * @Serializer\Type("float")
     */
    private $lng = '2.3613954';

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

    public function setLatLng($latlng)
    {
        ld($latlng['lat']);
        ld($latlng['lng']);
        ld('entra 123');
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
