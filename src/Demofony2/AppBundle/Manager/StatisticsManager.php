<?php

namespace Demofony2\AppBundle\Manager;

use Demofony2\AppBundle\Entity\ParticipationStatistics;
use Demofony2\AppBundle\Repository\ParticipationStatisticsRepository;
use Widop\GoogleAnalytics\Query;
use Widop\GoogleAnalytics\Service;

/**
 * StatisticsManager.
 */
class StatisticsManager
{
    protected $statisticsRepository;
    protected $client;
    protected $query;

    /**
     * @param ParticipationStatisticsRepository $sr
     * @param Service                           $service
     * @param Query                             $query
     */
    public function __construct(ParticipationStatisticsRepository $sr, Service $service, Query $query)
    {
        $this->statisticsRepository = $sr;
        $this->service = $service;
        $this->query = $query;
        $this->query->setMetrics(array('ga:sessions'));
    }

    public function addVote()
    {
        $statistics = $this->getStatisticsObject();
        $statistics->addVote();

        return $statistics;
    }

    public function addComment()
    {
        $statistics = $this->getStatisticsObject();
        $statistics->addComment();

        return $statistics;
    }

    public function addProposal()
    {
        $statistics = $this->getStatisticsObject();
        $statistics->addProposal();

        return $statistics;
    }

    private function getStatisticsObject()
    {
        $statistics = $this->statisticsRepository->findOneBy(array('day' => new \DateTime('TODAY')));

        if (!$statistics instanceof ParticipationStatistics) {
            return new ParticipationStatistics();
        }

        return $statistics;
    }

    /**
     * @param \Datetime $startAt datetime
     * @param \Datetime $endAt   datetime
     *
     * @return array
     */
    public function getParticipationStatistics($startAt, $endAt)
    {
        $interval = date_diff($startAt, $endAt);
        $numDays = $interval->format('%a');

        if ($numDays <= 15) {
            return $this->findStatistics($startAt, $endAt, 'day');
        } elseif ($numDays <= 60) {
            return $this->findStatistics($startAt, $endAt, 'week');
        }

        return $this->findStatistics($startAt, $endAt, 'month');
    }

    /**
     * @param \DateTime $startAt datetime
     * @param \DateTime $endAt   datetime
     *
     * @return array|int
     */
    public function getVisitsStatistics(\DateTime $startAt, \DateTime $endAt)
    {
        $interval = date_diff($startAt, $endAt);
        $numDays = $interval->format('%a');

        if ($numDays <= 15) {
            return $this->getVisitsByDay($startAt, $endAt);
        } elseif ($numDays <= 60) {
            return $this->getVisitsByWeek($startAt, $endAt);
        } elseif ($numDays >= 60 && $numDays<=365) {
            return $this->getVisitsByMonth($startAt, $endAt);
        }

        return $this->getVisitsByYear($startAt, $endAt);
    }

    protected function findStatistics($startAt, $endAt, $type = 'month')
    {
        return $this->statisticsRepository->getBetweenDate($startAt, $endAt, $type);
    }

    /**
     * @param \DateTime $startAt datetime
     * @param \DateTime $endAt   datetime
     *
     * @return array|int
     */
    public function getVisitsByDay(\DateTime $startAt, \DateTime $endAt)
    {
        $this->query->setDimensions(array('ga:date'));

        return $this->getGAVisits($startAt, $endAt);
    }

    /**
     * @param \DateTime $startAt datetime
     * @param \DateTime $endAt   datetime
     *
     * @return array|int
     */
    public function getVisitsByWeek(\DateTime $startAt, \DateTime $endAt)
    {
        $this->query->setDimensions(array('ga:week'));

        return $this->getGAVisits($startAt, $endAt);
    }

    /**
     * @param \DateTime $startAt datetime
     * @param \DateTime $endAt   datetime
     *
     * @return array|int
     */
    public function getVisitsByMonth(\DateTime $startAt, \DateTime $endAt)
    {
        $this->query->setDimensions(array('ga:month'));

        return $this->getGAVisits($startAt, $endAt);
    }

    /**
     * @param \DateTime $startAt datetime
     * @param \DateTime $endAt   datetime
     *
     * @return array|int
     */
    public function getVisitsByYear(\DateTime $startAt, \DateTime $endAt)
    {
        $this->query->setDimensions(array('ga:year'));

        return $this->getGAVisits($startAt, $endAt);
    }

    protected function getGAVisits($startAt, $endAt)
    {
        $this->query->setStartDate($startAt);
        $this->query->setEndDate($endAt);
        /** @var $result \Widop\GoogleAnalytics\Response */
        $result = $this->service->query($this->query);

        if (count($result->getRows()) > 0) {
            return $result->getRows();
        }

        return 0;
    }

    /**
     * Given a datetime, returns two datetimes, one with first second of the given day, and the other with the first second of next day.
     *
     * @param \Datetime $date
     *
     * @return array
     */
    protected function getDayRange($date = null)
    {
        $date = !is_object($date) ? new \DateTime('TODAY') : $date;
        $startAt = \DateTime::createFromFormat(
            'Y-m-d H:i:s',
            date('Y-m-d H:i:s', mktime(0, 0, 0, $date->format('m'), $date->format('d'), $date->format('Y')))
        );
        $endAt = clone $startAt;
        $endAt->modify('+1 day');

        return array($startAt, $endAt);
    }

    /**
     * Given a datetime, returns two datetimes, one with first day of the given month, and the other with the first day of next month.
     *
     * @param \Datetime $date
     *
     * @return array
     */
    protected function getMonthRange($date = null)
    {
        $date = !is_object($date) ? new \DateTime('TODAY') : $date;
        $startAt = \DateTime::createFromFormat(
            'Y-m-d H:i:s',
            date('Y-m-d H:i:s', mktime(0, 0, 0, $date->format('m'), 1, $date->format('Y')))
        );
        $endAt = clone $startAt;
        $endAt->modify('+1 month');

        return array($startAt, $endAt);
    }

    /**
     * Given a datetime, returns two datetimes, one with first day of the given year, and the other with the first day of next year.
     *
     * @param \Datetime $date
     *
     * @return array
     */
    protected function getYearRange($date = null)
    {
        $date = !is_object($date) ? new \DateTime('TODAY') : $date;
        $startAt = \DateTime::createFromFormat(
            'Y-m-d H:i:s',
            date('Y-m-d H:i:s', mktime(0, 0, 0, 1, 1, $date->format('Y')))
        );
        $endAt = clone $startAt;
        $endAt->modify('+1 year');

        return array($startAt, $endAt);
    }
}
