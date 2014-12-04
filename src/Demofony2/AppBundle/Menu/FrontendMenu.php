<?php

namespace Demofony2\AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\Translator;

/**
 * Class MenuBuilder
 *
 * @category Menu
 * @package  Demofony2\AppBundle\Menu
 * @author   David Romaní <david@flux.cat>
 */
class FrontendMenu
{
    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @var Translator
     */
    private $translator;

    /**
     * Contructor
     *
     * @param FactoryInterface $factory
     * @param Translator       $translator
     */
    public function __construct(FactoryInterface $factory, Translator $translator)
    {
        $this->factory = $factory;
        $this->translator = $translator;
    }

    /**
     * Create main menu
     *
     * @param Request $request
     * @return ItemInterface
     */
    public function createMainMenu(Request $request)
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav navbar-nav navbar-right');
        $menu->setChildrenAttribute('id', 'nav-accordion');
        $menu->setExtras(array('firstClass' => null));

        $menu->addChild('home', array(
                'label' => $this->translator->trans('front.home.home'),
                'route' => 'demofony2_front_homepage',
            ));
        $menu->addChild('properties', array(
                'label' => $this->translator->trans('front.home.transparency'),
                'route' => 'demofony2_front_transparency',
            ));
        $menu->addChild('about', array(
                'label' => $this->translator->trans('front.home.participation'),
                'route' => 'demofony2_front_participation',
            ));

        return $menu;
    }
}
