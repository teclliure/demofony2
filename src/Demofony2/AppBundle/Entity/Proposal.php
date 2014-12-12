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


/**
 * Proposal
 *
 * @ORM\Table(name="demofony2_proposal")
 * @ORM\Entity(repositoryClass="Demofony2\AppBundle\Repository\ProposalRepository")
 * @Gedmo\SoftDeleteable(fieldName="removedAt")
 */
class Proposal extends ParticipationBaseAbstract
{
    /**
     * @ORM\ManyToMany(targetEntity="Demofony2\AppBundle\Entity\Image")
     * @ORM\JoinTable(name="demofony2_proposal_images",
     *      joinColumns={@ORM\JoinColumn(name="proposal_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="image_id", referencedColumnName="id", unique=true)}
     *      )
     **/
    protected $images;

    /**
     * @ORM\ManyToMany(targetEntity="Demofony2\AppBundle\Entity\Document")
     * @ORM\JoinTable(name="demofony2_proposal_documents",
     *      joinColumns={@ORM\JoinColumn(name="proposal_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="document_id", referencedColumnName="id", unique=true)}
     *      )
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
     * @ORM\OneToMany(targetEntity="Demofony2\AppBundle\Entity\Comment", mappedBy="proposal", fetch="EXTRA_LAZY")
     **/
    protected $comments;

    /**
     * @ORM\ManyToMany(targetEntity="Demofony2\AppBundle\Entity\ProposalAnswer", cascade={"persist"})
     * @ORM\JoinTable(name="demofony2_proposal_proposal_answer",
     *      joinColumns={@ORM\JoinColumn(name="proposal_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="proposal_answer_id", referencedColumnName="id", unique=true)}
     *      )
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
}
