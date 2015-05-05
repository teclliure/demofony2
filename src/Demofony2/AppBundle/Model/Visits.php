<?php
namespace Demofony2\AppBundle\Model;

use JMS\Serializer\Annotation as Serializer;

class Visits
{
    /**
     * @var int
     * @Serializer\Groups({"list"})
     */
    protected $value;

    /**
     * @var \DateTime
     * @Serializer\Groups({"list"})
     */
    protected $date;

    public function __construct($date, $value)
    {
        $this->date = $date;
        $this->value = (int) $value;
    }

    /**
     * @return int
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }
}
