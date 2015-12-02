<?php

namespace Demofony2\AppBundle\Controller\Front;

use Demofony2\AppBundle\Entity\CategoryTransparency;
use Demofony2\AppBundle\Entity\DocumentTransparency;
use Demofony2\AppBundle\Entity\LawTransparency;
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
}
