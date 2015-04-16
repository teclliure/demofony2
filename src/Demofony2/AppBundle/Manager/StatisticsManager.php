<?php

namespace Demofony2\AppBundle\Manager;

use Demofony2\AppBundle\Entity\ParticipationStatistics;
use Demofony2\AppBundle\Repository\ParticipationStatisticsRepository;
use Widop\GoogleAnalytics\Query;
use Widop\GoogleAnalytics\Service;
use Widop\GoogleAnalytics\Response;

/**
 * StatisticsManager
 * @package Demofony2\AppBundle\Manager
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
        $statistics =  $this->statisticsRepository->findOneBy(array('day' => new \DateTime('TODAY')));

        if (!$statistics instanceof ParticipationStatistics) {
            return new ParticipationStatistics();
        }

        return $statistics;
    }

    public function getCommentsPublishedByDay(\DateTime $date = null)
    {
        list($startAt, $endAt) = $this->getDayRange($date);

        return (int) $this->getStatistics($startAt, $endAt, 'day');
    }

    public function getByMonth(\DateTime $date = null)
    {
        list($startAt, $endAt) = $this->getYearRange($date);

        return $this->getStatistics($startAt, $endAt, 'month');
    }

    public function getCommentsPublishedByYear(\DateTime $date = null)
    {
        list($startAt, $endAt) = $this->getYearRange($date);

        return (int) $this->getStatistics($startAt, $endAt, 'year');
    }

    protected function getStatistics($startAt, $endAt, $type = 'month')
    {
        return $this->statisticsRepository->getBetweenDate($startAt, $endAt, $type);
    }

    public function getVisitsByDay(\DateTime $date = null)
    {
        list($startAt, $endAt) = $this->getDayRange($date);

        return $this->getGAVisits($startAt, $endAt);
    }

    public function getVisitsByMonth(\DateTime $date = null)
    {
        list($startAt, $endAt) = $this->getMonthRange($date);

        return $this->getGAVisits($startAt, $endAt);
    }

    public function getVisitsByYear(\DateTime $date = null)
    {
        list($startAt, $endAt) = $this->getYearRange($date);

        return $this->getGAVisits($startAt, $endAt);
    }

    protected function getGAVisits($startAt, $endAt)
    {
        $this->query->setStartDate($startAt);
        $this->query->setEndDate($endAt);
        /** @var $result \Widop\GoogleAnalytics\Response */
        $result  = $this->service->query($this->query);

        if (count($result->getRows()) > 0) {
            return (int) $result->getRows()[0][0];
        }

        return 0;
    }

    /**
     * Given a datetime, returns two datetimes, one with first second of the given day, and the other with the first second of next day
     * @param \Datetime $date
     *
     * @return array
     */
    protected function getDayRange($date = null)
    {
        $date = !is_object($date) ? new \DateTime('TODAY') : $date;
        $startAt =  \DateTime::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s", mktime(0, 0, 0, $date->format('m'), $date->format('d'), $date->format('Y'))));
        $endAt = clone $startAt;
        $endAt->modify('+1 day');

        return array($startAt, $endAt);
    }

    /**
     * Given a datetime, returns two datetimes, one with first day of the given month, and the other with the first day of next month
     * @param \Datetime $date
     *
     * @return array
     */
    protected function getMonthRange($date = null)
    {
        $date = !is_object($date) ? new \DateTime('TODAY') : $date;
        $startAt =  \DateTime::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s", mktime(0, 0, 0, $date->format('m'), 1, $date->format('Y'))));
        $endAt = clone $startAt;
        $endAt->modify('+1 month');

        return array($startAt, $endAt);
    }

    /**
     * Given a datetime, returns two datetimes, one with first day of the given year, and the other with the first day of next year
     * @param \Datetime $date
     *
     * @return array
     */
    protected function getYearRange($date = null)
    {
        $date = !is_object($date) ? new \DateTime('TODAY') : $date;
        $startAt =  \DateTime::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s", mktime(0, 0, 0, 1, 1, $date->format('Y'))));
        $endAt = clone $startAt;
        $endAt->modify('+1 year');

        return array($startAt, $endAt);
    }
}
