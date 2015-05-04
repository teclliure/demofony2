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

use Demofony2\AppBundle\Manager\StatisticsManager;
use Sonata\BlockBundle\Block\BaseBlockService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

class ParticipationStatistics extends BaseBlockService
{
    protected $statisticsManager;

    public function __construct($name, EngineInterface $template, StatisticsManager $sm)
    {
        parent::__construct($name, $template);

        $this->statisticsManager = $sm;
    }

    public function setDefaultSettings(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                  'template' => 'Admin/Block/participationStatistics.html.twig',
            )
        );
    }

    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        // merge settings
        $settings = $blockContext->getSettings();

        return $this->renderResponse(
            $blockContext->getTemplate(),
            array(
                'block' => $blockContext->getBlock(),
                'settings' => $settings,
            ),
            $response
        );
    }
}
