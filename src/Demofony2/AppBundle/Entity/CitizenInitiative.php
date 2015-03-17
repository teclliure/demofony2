<?php
namespace Demofony2\AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

class CitizenInitiative extends BaseAbstract
{

    /**
     * @var string
     * @ORM\Column(name="title", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $title;

    /**
     * @var string
     * @ORM\Column(name="title", type="text")
     * @Assert\NotBlank()
     */
    private $description;

    /**
     * @var string
     * @ORM\Column(name="start_at", type="datetime")
     * @Assert\NotBlank()
     */
    private $startAt;

    /**
     * @var string
     * @ORM\Column(name="finish_at", type="datetime")
     * @Assert\NotBlank()
     */
    private $finishAt;


}