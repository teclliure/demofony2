<?php
/**
 * Demofony2
 *
 * @author: Marc Morales Valldepérez <marcmorales83@gmail.com>
 *
 * Date: 13/11/14
 * Time: 16:52
 */
namespace Demofony2\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Demofony2\AppBundle\Enum\ProposalStateEnum;
use JMS\Serializer\Annotation as Serializer;
use Demofony2\UserBundle\Entity\User;

/**
 * Proposal
 *
 * @ORM\Table(name="demofony2_proposal")
 * @ORM\Entity(repositoryClass="Demofony2\AppBundle\Repository\ProposalRepository")
 * @Gedmo\SoftDeleteable(fieldName="removedAt")
 */
class Proposal extends ParticipationBaseAbstract
{
    const DRAFT = ProposalStateEnum::DRAFT;
    const MODERATION_PENDING = ProposalStateEnum::MODERATION_PENDING;
    const DEBATE = ProposalStateEnum::DEBATE;
    const CLOSED = ProposalStateEnum::CLOSED;

    /**
     * @ORM\OneToMany(targetEntity="Demofony2\AppBundle\Entity\Image", mappedBy="proposal", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"position" = "ASC"})
     **/
    protected $images;

    /**
     * @ORM\OneToMany(targetEntity="Demofony2\AppBundle\Entity\Document", mappedBy="proposal", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"position" = "ASC"})
     **/
    protected $documents;

    /**
     * @ORM\ManyToOne(targetEntity="Demofony2\UserBundle\Entity\User", inversedBy="proposals")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     **/
    protected $author;

    /**
     * @ORM\ManyToMany(targetEntity="Demofony2\AppBundle\Entity\Category", inversedBy="proposals")
     * @ORM\JoinTable(name="demofony2_proposals_category")
     * @Serializer\Groups({"detail"})
     *
     **/
    protected $categories;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Demofony2\AppBundle\Entity\Comment", mappedBy="proposal", fetch="LAZY")
     **/
    protected $comments;

    /**
     * @ORM\OneToMany(targetEntity="Demofony2\AppBundle\Entity\ProposalAnswer", mappedBy="proposal", cascade={"persist", "remove"} , orphanRemoval=true)
     * @ORM\OrderBy({"position" = "ASC"})
     * @Serializer\Groups({"detail"})
     **/
    protected $proposalAnswers;

    /**
     * @var integer
     *
     * @ORM\Column(name="state", type="integer", nullable = true)
     * @Serializer\Groups({"detail"})
     */
    protected $state = ProposalStateEnum::DEBATE;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return string
     */
    public function getStateName()
    {
        return ProposalStateEnum::getTranslations()[$this->getState()];
    }

    /**
     * Add ProposalAnswers
     *
     * @param  ProposalAnswer            $proposalAnswer
     * @return Proposal
     */
    public function addProposalAnswer(ProposalAnswer $proposalAnswer)
    {
        $proposalAnswer->setProposal($this);
        $this->proposalAnswers[] = $proposalAnswer;

        return $this;
    }

    /**
     * Is user the author ?
     * @param User $user
     *
     * @return bool
     */
    public function isAuthor(User $user = null)
    {
        return $user && $user === $this->getAuthor();
    }
}
