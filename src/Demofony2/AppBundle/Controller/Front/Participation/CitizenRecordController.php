<?php

namespace Demofony2\AppBundle\Controller\Front\Participation;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class CitizenRecordController
 *
 * @category Controller
 *
 * @author   David Romaní <david@flux.cat>
 */
class CitizenRecordController extends Controller
{
    /**
     * @Route("/participacio/registre-ciutada/", name="demofony2_front_citizen_record")
     */
    public function citizenRecordAction()
    {
        return $this->render('Front/participation/citizen-record.html.twig');
    }
}
