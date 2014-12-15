<?php
/**
 * Demofony2
 *
 * @author: Marc Morales Valldepérez <marcmorales83@gmail.com>
 *
 * Date: 13/11/14
 * Time: 16:10
 */
namespace Demofony2\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Demofony2\AppBundle\Entity\Traits\ImageTrait;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Demofony2\AppBundle\Entity\Gps;
use Demofony2\AppBundle\Entity\ProcessParticipation;
use Demofony2\AppBundle\Entity\Proposal;
use Demofony2\AppBundle\Entity\Comment;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity
 * @ORM\Table(name="demofony2_user")
 * @Gedmo\SoftDeleteable(fieldName="removedAt")
 * @Vich\Uploadable
 */
class User  extends BaseUser
{
    use ImageTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Serializer\Groups({"list"})
     */
    protected $username;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime", nullable=true)
     * @var \DateTime
     */
    protected $updatedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var \DateTime
     */
    protected $removedAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var String
     * @Serializer\Groups({"list"})
     */
    protected $name;

    /**
     * @ORM\OneToOne(targetEntity="Demofony2\AppBundle\Entity\Gps",fetch="EAGER", orphanRemoval=true, cascade={"persist"})
     * @ORM\JoinColumn(name="gps_id", referencedColumnName="id")
    */
    protected $gps;

    /**
     * @Assert\File(
     *     groups={"Profile"},
     *     maxSize="500k",
     *     mimeTypes={"image/png", "image/jpeg", "image/pjpeg"},
     *     mimeTypesMessage = "constraint.mime_type"
     * )
     * @Vich\UploadableField(
     *     mapping="user_profile_image",
     *     fileNameProperty="imageName"
     * )
     * @var File $image
     */
    protected $image;

    /**
     * @ORM\OneToMany(targetEntity="Demofony2\AppBundle\Entity\ProcessParticipation", mappedBy="author")
     **/
    protected $processParticipations;

    /**
     * @ORM\OneToMany(targetEntity="Demofony2\AppBundle\Entity\Proposal", mappedBy="author")
     **/
    protected $proposals;

    /**
     * @ORM\OneToMany(targetEntity="Demofony2\AppBundle\Entity\Comment", mappedBy="author")
     **/
    protected $comments;

    /**
     * @var string
     * @Serializer\Groups({"list"})
     */
    protected $imageUrl;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @var String
     */
    protected $description;

    public function __construct()
    {
        parent::__construct();
        $this->processParticipations = new ArrayCollection();
        $this->proposals = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

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
     * Set createdAt
     *
     * @param  \DateTime $createdAt
     * @return User
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param  \DateTime $updatedAt
     * @return User
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set removedAt
     *
     * @param  \DateTime $removedAt
     * @return User
     */
    public function setRemovedAt($removedAt)
    {
        $this->removedAt = $removedAt;

        return $this;
    }

    /**
     * Get removedAt
     *
     * @return \DateTime
     */
    public function getRemovedAt()
    {
        return $this->removedAt;
    }

    /**
     * Set name
     *
     * @param  string $name
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set gps
     *
     * @param  Gps  $gps
     * @return User
     */
    public function setGps(Gps $gps = null)
    {
        $this->gps = $gps;

        return $this;
    }

    /**
     * Get gps
     *
     * @return Gps
     */
    public function getGps()
    {
        return $this->gps;
    }

    /**
     * Add processParticipations
     *
     * @param  ProcessParticipation $processParticipations
     * @return User
     */
    public function addProcessParticipation(ProcessParticipation $processParticipations)
    {
        $this->processParticipations[] = $processParticipations;

        return $this;
    }

    /**
     * Remove processParticipations
     *
     * @param ProcessParticipation $processParticipations
     */
    public function removeProcessParticipation(ProcessParticipation $processParticipations)
    {
        $this->processParticipations->removeElement($processParticipations);
    }

    /**
     * Get processParticipations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProcessParticipations()
    {
        return $this->processParticipations;
    }

    /**
     * Add proposals
     *
     * @param  Proposal $proposals
     * @return User
     */
    public function addProposal(Proposal $proposals)
    {
        $this->proposals[] = $proposals;

        return $this;
    }

    /**
     * Remove proposals
     *
     * @param Proposal $proposals
     */
    public function removeProposal(Proposal $proposals)
    {
        $this->proposals->removeElement($proposals);
    }

    /**
     * Get proposals
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProposals()
    {
        return $this->proposals;
    }

    /**
     * Add comments
     *
     * @param  Comment $comments
     * @return User
     */
    public function addComment(Comment $comments)
    {
        $this->comments[] = $comments;

        return $this;
    }

    /**
     * Remove comments
     *
     * @param Comment $comments
     */
    public function removeComment(Comment $comments)
    {
        $this->comments->removeElement($comments);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param $url
     *
     * @return User
     */
    public function setImageUrl($url)
    {
        $this->imageUrl = $url;

        return $this;
    }

    /**
     * @return string
     */
    public function getImageUrl()
    {
        return $this->imageUrl;
    }

    /**
     * @param $description
     *
     * @return User
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
}
