<?php

namespace Demofony2\AppBundle\Controller\Front;

use Demofony2\AppBundle\Entity\CategoryTransparency;
use Demofony2\AppBundle\Entity\DocumentTransparency;
use Demofony2\AppBundle\Entity\LawTransparency;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Class CalendarController.
 *
 * @category Controller
 *
 * @author   Marc Montañés <marc@teclliure.net>
 */
class CalendarController extends BaseController
{
    /**
     * @Route("/calendar/", name="demofony2_front_calendar")
     *
     * @return Response
     */
    public function showCalendarAction()
    {
        $em = $this->getDoctrine()->getManager();
        $events = $em->getRepository('Demofony2AppBundle:CalendarSubevent')->findBy([], ['startAt' => 'DESC']);

        return $this->render('Front/calendar/calendar.html.twig', array(
            'events'      => $events
            )
        );
    }

    /**
     * @Route("/calendar/events", name="demofony2_calendar_events", options={"expose"=true})
     *
     * @return Response
     */
    public function calendarEventsAction(Request $request)
    {
        $eventsArray = array(
            'success'   => 1,
            'result'    => array()
        );
        $em = $this->getDoctrine()->getManager();

        $from = new \Datetime('@'.($request->get('from')/1000));
        $to = new \Datetime('@'.($request->get('to')/1000));

        $events = $em->createQuery(
            'SELECT c,s FROM Demofony2AppBundle:CalendarEvent c JOIN c.subevents s WHERE (s.startAt >= :from AND s.startAt <= :to) OR (s.finishAt > :from AND s.finishAt <= :to)  AND c.published = 1'
        )
        ->setParameters(array(
            'from'  => $from,
            'to'    => $to
        ))
        ->getResult();

        foreach ($events as $event) {
            $subevents = $event->getSubevents();
            foreach ($subevents as $subevent) {
                $color = $subevent->getColor();
                if (!$color) {
                    $color = $event->getColor();
                }
                $eventArray = array(
                    'id'    => $subevent->getId(),
                    'title' => $event->getTitle().' - '.$subevent->getTitle(),
                    'url'   => $this->generateUrl('demofony2_calendar_show_event', array('id'=>$subevent->getId())),
                    'color' => $color,
                    'start' => $subevent->getStartAt()->getTimestamp() * 1000, // Milliseconds
                    'end'   => $subevent->getFinishAt()->getTimestamp() * 1000 // Milliseconds

                );
                $eventsArray['result'][] = $eventArray;
            }
        }

        $response = new JsonResponse();
        $response->setData(
            $eventsArray
        );

        return $response;
    }

    /**
     * @Route("/calendar/showEvent/{id}", name="demofony2_calendar_show_event", options={"expose"=true})
     *
     * @return Response
     */
    public function calendarShowEventAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $subevent = $em->createQuery(
            'SELECT s,e FROM Demofony2AppBundle:CalendarSubevent s JOIN s.event e WHERE s.id = :id'
        )
        ->setParameters(array(
            'id'  => $request->get('id')
        ))
        ->getOneOrNullResult();

        return $this->render('Front/calendar/showEvent.html.twig', array(
                'subevent'      => $subevent,
            )
        );
    }
}
