<?php

/**
 * Demofony2.
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
use Demofony2\AppBundle\Entity\Traits\ImageCropTrait;
use Demofony2\AppBundle\Entity\CitizenForum;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="Demofony2\UserBundle\Repository\UserRepository")
 * @ORM\Table(name="demofony2_user")
 * @Gedmo\SoftDeleteable(fieldName="removedAt")
 * @UniqueEntity("email")
 * @Vich\Uploadable
 */
class User  extends BaseUser
{
//    use ImageTrait;
    use ImageCropTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Serializer\Groups({"list", "children-list"})
     *
     * @Assert\Regex(groups={"Registration", "Profile"},
     *     pattern     = "/^[a-zA-Z0-9_]+$/",
     *     htmlPattern = false,
     *     message = "user.registration.username-pattern"
     * )
     * @Assert\NotBlank(groups={"Registration", "Profile"})
     * @Assert\Length(groups={"Registration", "Profile"},
     *      min = "3",
     *      max = "15",
     *      minMessage = "user.registration.min_length_username",
     *      maxMessage = "user.registration.max_length_username"
     * )
     */
    protected $username;

    /**
     * @Assert\NotNull(groups={"Registration", "ResetPassword", "ChangePassword"})
     * @Assert\Length(groups={"Registration", "ResetPassword", "ChangePassword"},
     *      min = "6",
     *      max = "40",
     *      minMessage = "user.registration.min_length_password",
     *      maxMessage = "user.registration.max_lengt_password"
     * )
     * @Assert\NotBlank(groups={"Registration", "ResetPassword", "ChangePassword"})
     */
    protected $plainPassword;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     *
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    protected $updatedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    protected $removedAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @var String
     * @Serializer\Groups({"list", "children-list"})
     * @Assert\NotBlank(groups={"Profile"})
     */
    protected $name;

    /**
     * @ORM\OneToOne(targetEntity="Demofony2\AppBundle\Entity\Gps",fetch="EAGER", orphanRemoval=true, cascade={"persist"})
     * @ORM\JoinColumn(name="gps_id", referencedColumnName="id")
     */
    protected $gps;
//
//    /**
//     * @Assert\File(
//     *     groups={"Profile"},
//     *     maxSize="500k",
//     *     mimeTypes={"image/png", "image/jpeg", "image/pjpeg"},
//     *     mimeTypesMessage = "constraint.mime_type"
//     * )
//     * @Vich\UploadableField(
//     *     mapping="user_profile_image",
//     *     fileNameProperty="imageName"
//     * )
//     * @var File $image
//     */
//    protected $image;

    /**
     * @ORM\OneToMany(targetEntity="Demofony2\AppBundle\Entity\ProcessParticipation", mappedBy="author", fetch="EXTRA_LAZY")
     **/
    protected $processParticipations;

    /**
     * @ORM\OneToMany(targetEntity="Demofony2\AppBundle\Entity\Proposal", mappedBy="author", fetch="EXTRA_LAZY")
     **/
    protected $proposals;

    /**
     * @ORM\OneToMany(targetEntity="Demofony2\AppBundle\Entity\CitizenForum", mappedBy="author", fetch="EXTRA_LAZY")
     **/
    protected $citizenForums;

    /**
     * @ORM\OneToMany(targetEntity="Demofony2\AppBundle\Entity\Comment", mappedBy="author", fetch="EXTRA_LAZY")
     **/
    protected $comments;

    /**
     * @var string
     * @Serializer\Groups({"list", "children-list"})
     */
    protected $imageUrl;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     * @var String
     */
    protected $description;

    /** @ORM\Column(name="facebook_id", type="string", length=255, nullable=true) */
    protected $facebookId;

    /** @ORM\Column(name="facebook_access_token", type="string", length=255, nullable=true) */
    protected $facebookAccessToken;

    /** @ORM\Column(name="google_id", type="string", length=255, nullable=true) */
    protected $googleId;

    /** @ORM\Column(name="google_access_token", type="string", length=255, nullable=true) */
    protected $googleAccessToken;

    /** @ORM\Column(name="twitter_id", type="string", length=255, nullable=true) */
    protected $twitterId;

    /** @ORM\Column(name="twitter_access_token", type="string", length=255, nullable=true) */
    protected $twitterAccessToken;

    /**
     * @var bool
     *
     * @ORM\Column(name="newsletter_subscribed", type="boolean")
     */
    protected $newsletterSubscribed;

    public function __construct()
    {
        parent::__construct();
        $this->processParticipations = new ArrayCollection();
        $this->proposals = new ArrayCollection();
        $this->citizenForums = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->gps = new Gps();
        $this->newsletterSubscribed = true;
    }

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
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return User
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt.
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt.
     *
     * @param \DateTime $updatedAt
     *
     * @return User
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt.
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set removedAt.
     *
     * @param \DateTime $removedAt
     *
     * @return User
     */
    public function setRemovedAt($removedAt)
    {
        $this->removedAt = $removedAt;

        return $this;
    }

    /**
     * Get removedAt.
     *
     * @return \DateTime
     */
    public function getRemovedAt()
    {
        return $this->removedAt;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return User
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
     * Set gps.
     *
     * @param Gps $gps
     *
     * @return User
     */
    public function setGps(Gps $gps = null)
    {
        $this->gps = $gps;

        return $this;
    }

    /**
     * Get gps.
     *
     * @return Gps
     */
    public function getGps()
    {
        return $this->gps;
    }

    /**
     * Add processParticipations.
     *
     * @param ProcessParticipation $processParticipations
     *
     * @return User
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
     * Add proposals.
     *
     * @param Proposal $proposals
     *
     * @return User
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
     * Add CitizenForums.
     *
     * @param CitizenForum $citizenForums
     *
     * @return User
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

    /**
     * Add comments.
     *
     * @param Comment $comments
     *
     * @return User
     */
    public function addComment(Comment $comments)
    {
        $this->comments[] = $comments;

        return $this;
    }

    /**
     * Remove comments.
     *
     * @param Comment $comments
     */
    public function removeComment(Comment $comments)
    {
        $this->comments->removeElement($comments);
    }

    /**
     * Get comments.
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

    /**
     * @param $facebookId
     *
     * @return User
     */
    public function setFacebookId($facebookId)
    {
        $this->facebookId = $facebookId;

        return $this;
    }

    /**
     * @return string
     */
    public function getFacebookId()
    {
        return $this->facebookId;
    }

    /**
     * @param $facebookAccessToken
     *
     * @return User
     */
    public function setFacebookAccessToken($facebookAccessToken)
    {
        $this->facebookAccessToken = $facebookAccessToken;

        return $this;
    }

    /**
     * @return string
     */
    public function getFacebookAccessToken()
    {
        return $this->facebookAccessToken;
    }

    /**
     * @param $twitterId
     *
     * @return User
     */
    public function setTwitterId($twitterId)
    {
        $this->twitterId = $twitterId;

        return $this;
    }

    /**
     * @return string
     */
    public function getTwitterId()
    {
        return $this->twitterId;
    }

    /**
     * @param $twitterAccessToken
     *
     * @return User
     */
    public function setTwitterAccessToken($twitterAccessToken)
    {
        $this->twitterAccessToken = $twitterAccessToken;

        return $this;
    }

    /**
     * @return string
     */
    public function getTwitterAccessToken()
    {
        return $this->twitterAccessToken;
    }

    /**
     * @param $googleId
     *
     * @return User
     */
    public function setGoogleId($googleId)
    {
        $this->googleId = $googleId;

        return $this;
    }

    /**
     * @return string
     */
    public function getGoogleId()
    {
        return $this->googleId;
    }

    /**
     * @param $googleAccessToken
     *
     * @return User
     */
    public function setGoogleAccessToken($googleAccessToken)
    {
        $this->googleAccessToken = $googleAccessToken;

        return $this;
    }

    /**
     * @return string
     */
    public function getGoogleAccessToken()
    {
        return $this->googleAccessToken;
    }

    /**
     * @return bool
     */
    public function getNewsletterSubscribed()
    {
        return $this->newsletterSubscribed;
    }

    /**
     * @param bool $newsletterSubscribed
     *
     * @return User
     */
    public function setNewsletterSubscribed($newsletterSubscribed)
    {
        $this->newsletterSubscribed = $newsletterSubscribed;

        return $this;
    }

    /**
     * Get Roles (security).
     *
     * @return array
     */
    public function getRoles()
    {
        $roles = parent::getRoles();

        if (empty($this->name)) {
            $roles[] = 'ROLE_PENDING_COMPLETE_PROFILE';

            return $roles;
        }

        return $roles;
    }

    public function getUploadDir()
    {
        return 'uploads/images/user';
    }

    /**
     * @return mixed
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param mixed $plainPassword
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
    }


}
