<?php

namespace Demofony2\AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;
use Demofony2\AppBundle\Entity\Traits\ImageCropTrait;

/**
 * Category.
 *
 * @ORM\Table(name="demofony2_category")
 * @ORM\Entity
 * @Gedmo\SoftDeleteable(fieldName="removedAt")
 * @Vich\Uploadable
 */
class Category extends BaseAbstract
{
    use ImageCropTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Serializer\Groups({"detail"})
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     * @Serializer\Groups({"detail"})
     */
    private $description;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Demofony2\AppBundle\Entity\Proposal", mappedBy="categories")
     **/
    private $proposals;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Demofony2\AppBundle\Entity\ProcessParticipation", mappedBy="categories")
     **/
    private $processParticipations;

    /**
     * @ORM\ManyToMany(targetEntity="Demofony2\AppBundle\Entity\CitizenForum", mappedBy="categories")
     **/
    protected $citizenForums;

//    /**
//     * @Assert\File(
//     *     groups={"Profile"},
//     *     maxSize="1M",
//     *     mimeTypes={"image/png", "image/jpeg", "image/pjpeg"},
//     *     mimeTypesMessage = "constraint.mime_type"
//     * )
//     * @Vich\UploadableField(
//     *     mapping="category_image",
//     *     fileNameProperty="imageName"
//     * )
//     * @var File $image
//     */
//    protected $image;

    public function __construct()
    {
        $this->proposals = new ArrayCollection();
        $this->processParticipations = new ArrayCollection();
    }
    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Category
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description.
     *
     * @param string $description
     *
     * @return Category
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Add proposals.
     *
     * @param Proposal $proposals
     *
     * @return Category
     */
    public function addProposal(Proposal $proposals)
    {
        $this->proposals[] = $proposals;

        return $this;
    }

    /**
     * Remove proposals.
     *
     * @param Proposal $proposals
     */
    public function removeProposal(Proposal $proposals)
    {
        $this->proposals->removeElement($proposals);
    }

    /**
     * Get proposals.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProposals()
    {
        return $this->proposals;
    }

    /**
     * Add processParticipations.
     *
     * @param ProcessParticipation $processParticipations
     *
     * @return Category
     */
    public function addProcessParticipation(ProcessParticipation $processParticipations)
    {
        $this->processParticipations[] = $processParticipations;

        return $this;
    }

    /**
     * Remove processParticipations.
     *
     * @param ProcessParticipation $processParticipations
     */
    public function removeProcessParticipation(ProcessParticipation $processParticipations)
    {
        $this->processParticipations->removeElement($processParticipations);
    }

    /**
     * Get processParticipations.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProcessParticipations()
    {
        return $this->processParticipations;
    }

    /**
     * Add CitizenForums.
     *
     * @param CitizenForum $citizenForums
     *
     * @return Category
     */
    public function addCitizenForum(CitizenForum $citizenForums)
    {
        $this->citizenForums[] = $citizenForums;

        return $this;
    }

    /**
     * Remove CitizenForums.
     *
     * @param CitizenForum $citizenForums
     */
    public function removeCitizenForum(CitizenForum $citizenForums)
    {
        $this->citizenForums->removeElement($citizenForums);
    }

    /**
     * Get CitizenForums.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCitizenForums()
    {
        return $this->citizenForums;
    }

    public function __toString()
    {
        return $this->getName();
    }
}
