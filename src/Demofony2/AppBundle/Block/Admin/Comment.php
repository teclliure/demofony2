<?php

/**
 * Demofony2.
 *
 * @author: Marc Morales Valldepérez <marcmorales83@gmail.com>
 *
 * Date: 30/01/15
 * Time: 11:05
 */
namespace Demofony2\AppBundle\Block\Admin;

use Doctrine\Common\Persistence\ObjectManager;
use Sonata\BlockBundle\Block\BaseBlockService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

class Comment extends BaseBlockService
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
                  'template' => 'Admin/Block/comment.html.twig',
            )
        );
    }

    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        // merge settings
        $settings = $blockContext->getSettings();

        $count = $this->em->getRepository('Demofony2AppBundle:Comment')->getUnrevisedCount();

        return $this->renderResponse(
            $blockContext->getTemplate(),
            array(
                'count' => $count,
                'block' => $blockContext->getBlock(),
                'settings' => $settings,
            ),
            $response
        );
    }
}
