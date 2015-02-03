<?php
/**
 * Demofony2
 *
 * @author: Marc Morales Valldepérez <marcmorales83@gmail.com>
 *
 * Date: 30/01/15
 * Time: 11:52
 */
namespace Demofony2\AppBundle\Block\Admin;

use Sonata\BlockBundle\Block\BaseBlockService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Widop\GoogleAnalytics\Client;

class Analytics extends BaseBlockService
{
    protected $client;
    protected $profileId;

    public function __construct($name, EngineInterface $template, Client $client, $profileId)
    {
        parent::__construct($name, $template);

        $this->client = $client;
        $this->profileId = $profileId;
    }

    public function setDefaultSettings(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                  'template' => 'Admin/Block/analytics.html.twig',
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
                'client' => $this->client,
                'profileId' => $this->profileId,
                'block' => $blockContext->getBlock(),
                'settings' => $settings,
            ),
            $response
        );
    }
}
