<?php

namespace Demofony2\AppBundle\Manager;

use BladeTester\CalendarBundle\Entity\Event;
use BladeTester\CalendarBundle\Entity\EventCategory;
use Demofony2\AppBundle\Entity\CalendarEvent;
use Demofony2\AppBundle\Entity\CitizenForum;
use Demofony2\AppBundle\Entity\CitizenInitiative;
use Demofony2\AppBundle\Entity\Comment;
use Demofony2\AppBundle\Entity\ProcessParticipation;
use Demofony2\AppBundle\Entity\Proposal;
use Demofony2\AppBundle\Form\Type\Api\CommentType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormTypeInterface;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpKernel\Exception\HttpException;
use FOS\RestBundle\Util\Codes;
use Demofony2\UserBundle\Entity\User;
use Demofony2\AppBundle\Entity\ProposalAnswer;
use Demofony2\AppBundle\Form\Type\Api\VoteType;
use Demofony2\AppBundle\Entity\Vote;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Demofony2\AppBundle\Enum\ProposalStateEnum;

class CalendarManager extends AbstractManager
{

    const PROPOSAL = 'proposal';
    const PROCESS_PARTICIPATION = 'process_participation';
    const CITIZEN_INITIATIVE = 'citizen_initiative';
    const CITIZEN_FORUM = 'citizen_forum';

    protected $eventCategories = [
        [
            'name' => self::PROPOSAL,
            'color' => 'primary',
        ],
        [
            'name' => self::PROCESS_PARTICIPATION,
            'color' => 'success',
        ],
        [
            'name' => self::CITIZEN_INITIATIVE,
            'color' => 'warning',
        ],
        [
            'name' => self::CITIZEN_FORUM,
            'color' => 'info',
        ],
    ];

    /**
     * @param ObjectManager      $em
     * @param ValidatorInterface $validator
     */
    public function __construct(ObjectManager $em, ValidatorInterface $validator)
    {
        parent::__construct($em, $validator);
    }

    /**
     * @return \Demofony2\AppBundle\Repository\CalendarEventRepository
     */
    public function getRepository()
    {
        return $this->em->getRepository('Demofony2AppBundle:CalendarEvent');
    }

    /**
     * @return CalendarEvent
     */
    public function create()
    {
        return new CalendarEvent();
    }

    /**
     * @param  int          $id
     * @param  string       $title
     * @param  \DateTime    $date
     * @param EventCategory $category
     *
     * @return CalendarEvent
     */
    public function createEvent($id, $title, $date, EventCategory $category)
    {
        $event = $this->create();
        $event->setTitle($title)
            ->setEntityId($id)
            ->setStart($date)
            ->setEnd($date)
            ->setCategory($category);

        return $event;
    }

    public function createOrUpdateProposalEvent(Proposal $object)
    {
        $category = $this->getEventCategory(self::PROPOSAL);
        $event = $this->getRepository()->findOneByCategoryAndEntityId($category, $object->getId());

        if ($event) {
            $this->em->remove($event);
        }

        if ($object->getUserDraft() === true || $object->getModerated() === false) {
            return;
        }
        $event = $this->createEvent(
            $object->getId(),
            $object->getTitle(),
            $object->getUpdatedAt(),
            $category
        );

        $this->persist($event, false);

        return $event;
    }

    public function createOrUpdateProcessParticipationEvent(ProcessParticipation $object)
    {
        $category = $this->getEventCategory(self::PROCESS_PARTICIPATION);
        $event = $this->getRepository()->findOneByCategoryAndEntityId($category, $object->getId());

        if ($event) {
            $this->em->remove($event);
        }

        if (!$object->getPublished()) {
            return;
        }

        return $this->createEvent(
            $object->getId(),
            $object->getTitle(),
            $object->getUpdatedAt(),
            $category
        );

    }

    public function createOrUpdateCitizenInitiativeEvent(CitizenInitiative $object)
    {
        $category = $this->getEventCategory(self::CITIZEN_INITIATIVE);
        $event = $this->getRepository()->findOneByCategoryAndEntityId($category, $object->getId());

        if ($event) {
            $this->em->remove($event);
        }

        if (!$object->getPublished()) {
            return;
        }

        return $this->createEvent(
            $object->getId(),
            $object->getTitle(),
            $object->getStartAt(),
            $category
        );
    }

    public function createOrUpdateCitizenForumEvent(CitizenForum $object)
    {
        $category = $this->getEventCategory(self::CITIZEN_FORUM);
        $event = $this->getRepository()->findOneByCategoryAndEntityId($category, $object->getId());

        if ($event) {
            $this->em->remove($event);
        }

        if (!$object->getPublished()) {
            return;
        }

        return $this->createEvent(
            $object->getId(),
            $object->getTitle(),
            $object->getUpdatedAt(),
            $category
        );
    }

    public function getEventCategory($category)
    {
        $category = $this->em->getRepository('BladeTesterCalendarBundle:EventCategory')->findOneBy(
            array('name' => $category)
        );

        if (!$category) {
            $this->createEventCategories();
            $category = $this->em->getRepository('BladeTesterCalendarBundle:EventCategory')->findOneBy(
                array('name' => $category)
            );
        }

        return $category;
    }


    public function createEventCategories()
    {
        foreach ($this->eventCategories as $eventCategory) {
            $category = new EventCategory();
            $category->setName($eventCategory['name'])
                ->setColor($eventCategory['color']);
            $this->persist($category, false);
        }
        $this->flush();
    }
}
