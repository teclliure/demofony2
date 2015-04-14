<?php
namespace Demofony2\AppBundle\Controller\Api;

use Demofony2\AppBundle\Entity\Proposal;
use Demofony2\AppBundle\Entity\ProposalAnswer;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * StatisticsController
 * @Rest\NamePrefix("api_")
 * @Security("has_role('ROLE_ADMIN')")
 */
class StatisticsController extends FOSRestController
{
    /**
     *
     * @ApiDoc(
     *                           section="Statistics",
     *                           resource=true,
     *                           description="Get proposal",
     *                           statusCodes={
     *                           200="Returned when successful",
     *                           404={
     *                           "Returned when proposal not found",
     *                           }
     *                           }
     *                           )
     * @Rest\Get("/statistics/participation")
     *
     */
    public function getParticipationAction()
    {
        return [$this->getStatisticsManager()->getByMonth()];
    }

    public function getStatisticsManager()
    {
        return $this->get('app.statistics');
    }
}
