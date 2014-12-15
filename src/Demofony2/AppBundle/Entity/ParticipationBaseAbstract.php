<?php

namespace Demofony2\AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Demofony2\UserBundle\Entity\User;
use JMS\Serializer\Annotation as Serializer;

/**
 * ParticipationBaseAbstract
 */
class ParticipationBaseAbstract extends BaseAbstract implements UserAwareInterface
{
    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     * @Serializer\Groups({"detail"})
     */
    protected $title;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    protected $commentsModerated = true;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable = true)
     * @Serializer\Groups({"detail"})
     */
    protected $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="finished_at", type="datetime")
     * @Serializer\Groups({"detail"})
     */
    protected $finishAt;

    /**
     * @var ArrayCollection
     **/
    protected $comments;

    /**
     * @var User
     */
    protected $author;

    /**
     * @var ArrayCollection
     */
    protected $categories;

    /**
     * @var ArrayCollection
     */
    protected $proposalAnswers;

    /**
     * @ORM\OneToOne(targetEntity="Demofony2\AppBundle\Entity\InstitutionalAnswer",fetch="EAGER", orphanRemoval=true, cascade={"persist"})
     * @ORM\JoinColumn(name="institutional_answer_id", referencedColumnName="id")
     */
    protected $institutionalAnswer;

    /**
     * @ORM\OneToOne(targetEntity="Demofony2\AppBundle\Entity\Gps",fetch="EAGER", orphanRemoval=true, cascade={"persist"})
     * @ORM\JoinColumn(name="gps_id", referencedColumnName="id")
     * @Serializer\Groups({"detail"})
     */
    protected $gps;

    /**
     * @var boolean
     * @Serializer\Type("boolean")
     * @Serializer\Groups({"detail"})
     */
    protected $userAlreadyVote;

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->documents = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->proposalAnswers = new ArrayCollection();
    }

    /**
     * Set title
     *
     * @param  string                    $title
     * @return ParticipationBaseAbstract
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set commentsModerated
     *
     * @param  boolean                   $commentsModerated
     * @return ParticipationBaseAbstract
     */
    public function setCommentsModerated($commentsModerated)
    {
        $this->commentsModerated = $commentsModerated;

        return $this;
    }

    /**
     * Get commentsModerated
     *
     * @return boolean
     */
    public function getCommentsModerated()
    {
        return $this->commentsModerated;
    }

    /**
     * Set description
     *
     * @param  string                    $description
     * @return ParticipationBaseAbstract
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set finishAt
     *
     * @param  \DateTime                 $finishAt
     * @return ParticipationBaseAbstract
     */
    public function setFinishAt($finishAt)
    {
        $this->finishAt = $finishAt;

        return $this;
    }

    /**
     * Get finishAt
     *
     * @return \DateTime
     */
    public function getFinishAt()
    {
        return $this->finishAt;
    }

    /**
     * Add Images
     *
     * @param  Image                     $image
     * @return ParticipationBaseAbstract
     */
    public function addImage(Image $image)
    {
        $this->images[] = $image;

        return $this;
    }

    /**
     * Remove Images
     *
     * @param Image $image
     */
    public function removeImage(Image $image)
    {
        $this->images->removeElement($image);
    }

    /**
     * Get Images
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Add Documents
     *
     * @param  Document                  $document
     * @return ParticipationBaseAbstract
     */
    public function addDocument(Document $document)
    {
        $this->documents[] = $document;

        return $this;
    }

    /**
     * Remove Documents
     *
     * @param Document $document
     */
    public function removeDocument(Document $document)
    {
        $this->documents->removeElement($document);
    }

    /**
     * Get Documents
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDocuments()
    {
        return $this->documents;
    }

    /**
     * Set author
     *
     * @param User $author
     *
     * @return ProcessParticipation
     */
    public function setAuthor(User $author = null)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     * @return User
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Add Comments
     *
     * @param  Comment                   $comment
     * @return ParticipationBaseAbstract
     */
    public function addComment(Comment $comment)
    {
        $this->comments[] = $comment;

        return $this;
    }

    /**
     * Remove Comments
     *
     * @param Comment $comment
     */
    public function removeComment(Comment $comment)
    {
        $this->comments->removeElement($comment);
    }

    /**
     * Get Comments
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Add Categories
     *
     * @param  Category                  $category
     * @return ParticipationBaseAbstract
     */
    public function addCategory(Category $category)
    {
        $this->categories[] = $category;

        return $this;
    }

    /**
     * Remove Categories
     *
     * @param Category $category
     */
    public function removeCategory(Category $category)
    {
        $this->categories->removeElement($category);
    }

    /**
     * Get Categories
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param $categories
     *
     * @return ParticipationBaseAbstract
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * Add ProposalAnswers
     *
     * @param  ProposalAnswer            $proposalAnswer
     * @return ParticipationBaseAbstract
     */
    public function addProposalAnswer(ProposalAnswer $proposalAnswer)
    {
        $this->proposalAnswers[] = $proposalAnswer;

        return $this;
    }

    /**
     * Remove ProposalAnswers
     *
     * @param ProposalAnswer $proposalAnswer
     */
    public function removeProposalAnswer(ProposalAnswer $proposalAnswer)
    {
        $this->proposalAnswers->removeElement($proposalAnswer);
    }

    /**
     * Get ProposalAnswers
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProposalAnswers()
    {
        return $this->proposalAnswers;
    }

    /**
     * Set institutionalAnswer
     *
     * @param  InstitutionalAnswer       $institutionalAnswer
     * @return ParticipationBaseAbstract
     */
    public function setInstitutionalAnswer($institutionalAnswer)
    {
        $this->institutionalAnswer = $institutionalAnswer;

        return $this;
    }

    /**
     * Get institutionalAnswer
     *
     * @return boolean
     */
    public function getInstitutionalAnswer()
    {
        return $this->institutionalAnswer;
    }

    /**
     * Set userAlreadyVote
     * @param $vote
     *
     * @return boolean
     */
    public function setUserAlreadyVote($vote)
    {
        $this->userAlreadyVote = $vote;

        return $this;
    }

    /**
     * Get userAlreadyVote
     *
     * @return boolean
     */
    public function getUserAlreadyVote()
    {
        return $this->userAlreadyVote;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getTitle();
    }

    public function getState()
    {
        return $this->state;
    }

    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Set gps
     *
     * @param  Gps                       $gps
     * @return ParticipationBaseAbstract
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
     * @return int
     * @Serializer\VirtualProperty
     * @Serializer\Groups({"detail"})
     */
    public function getTotalVotesCount()
    {
        $result = 0;

        foreach ($this->getProposalAnswers() as $proposalAnswer) {
            $result += $proposalAnswer->getVotes()->count();
        }

        return $result;
    }
}
