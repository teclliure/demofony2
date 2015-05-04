<?php

/**
 * Demofony2.
 *
 * @author: Marc Morales Valldepérez <marcmorales83@gmail.com>
 *
 * Date: 30/01/15
 * Time: 11:52
 */
namespace Demofony2\AppBundle\Block\Admin;

use Doctrine\Common\Persistence\ObjectManager;
use Sonata\BlockBundle\Block\BaseBlockService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

class Proposal extends BaseBlockService
{
    protected $em;

    public function __construct($name, EngineInterface $template, ObjectManager $om)
    {
        parent::__construct($name, $template);

        $this->em = $om;
    }

    public function setDefaultSettings(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                  'type' => 'test',
                  'template' => 'Admin/Block/proposal.html.twig',
            )
        );
    }

    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        // merge settings
        $settings = $blockContext->getSettings();

        $debateCount = $this->em->getRepository('Demofony2AppBundle:Proposal')->getVotePeriodCount();
        $closedWithoutInstitutionalAnswer = $this->em->getRepository('Demofony2AppBundle:Proposal')->getClosedWithoutInstitutionalAnswerCount();

        return $this->renderResponse(
            $blockContext->getTemplate(),
            array(
                'debateCount' => $debateCount,
                'closedWithoutInstitutionalAnswer' => $closedWithoutInstitutionalAnswer,
                'block' => $blockContext->getBlock(),
                'settings' => $settings,
            ),
            $response
        );
    }
}
