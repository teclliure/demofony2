<?php

namespace Demofony2\AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\Translator;

/**
 * Class MenuBuilder.
 *
 * @category Menu
 *
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
     * Contructor.
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
     * Create main menu.
     *
     * @param Request $request
     *
     * @return ItemInterface
     */
    public function createMainMenu(Request $request)
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav navbar-nav navbar-right');
        $menu->setChildrenAttribute('id', 'nav-accordion');
        $menu->setExtras(array('firstClass' => null));

        $menu->addChild('home', array(
                'label' => 'front.home.home',
                'route' => 'demofony2_front_homepage',
            ));
        $menu->addChild('transparency', array(
                'label' => 'front.home.transparency',
                'route' => 'demofony2_front_transparency',
                'current' => $request->get('_route') == 'demofony2_front_transparency' ||
                    $request->get('_route') == 'demofony2_front_transparency_list' ||
                    $request->get('_route') == 'demofony2_front_transparency_detail' ||
                    $request->get('_route') == 'demofony2_front_transparency_law_detail' ||
                    $request->get('_route') == 'demofony2_front_cms_rendering_account' ||
                    $request->get('_route') == 'demofony2_front_cms_collaborates' ||
                    $request->get('_route') == 'demofony2_front_cms_transparency_law' ||
                    $request->get('_route') == 'demofony2_front_cms_public_information',
            ));
        $menu->addChild('participation', array(
                'label' => 'front.home.participation',
                'route' => 'demofony2_front_participation',
                'current' => $request->get('_route') == 'demofony2_front_participation' ||
                    $request->get('_route') == 'demofony2_front_participation_calendar' ||
                    $request->get('_route') == 'demofony2_front_participation_discussions' ||
                    $request->get('_route') == 'demofony2_front_participation_discussions_edit' ||
                    $request->get('_route') == 'demofony2_front_participation_discussions_list_open' ||
                    $request->get('_route') == 'demofony2_front_participation_discussions_list_closed' ||
                    $request->get('_route') == 'demofony2_front_participation_discussion_show_page' ||
                    $request->get('_route') == 'demofony2_front_participation_citizen_forums' ||
                    $request->get('_route') == 'demofony2_front_participation_citizen_forums_edit' ||
                    $request->get('_route') == 'demofony2_front_participation_citizen_initiative_list_open' ||
                    $request->get('_route') == 'demofony2_front_participation_citizen_initiative_list_closed' ||
                    $request->get('_route') == 'demofony2_front_participation_citizen_initiative_detail' ||
                    $request->get('_route') == 'demofony2_front_participation_citizen_forums_edit' ||
                    $request->get('_route') == 'demofony2_front_participation_proposals' ||
                    $request->get('_route') == 'demofony2_front_participation_proposals_list_open' ||
                    $request->get('_route') == 'demofony2_front_participation_proposals_list_closed' ||
                    $request->get('_route') == 'demofony2_front_participation_proposals_edit' ||
                    $request->get('_route') == 'demofony2_front_participation_proposals_show' ||
                    $request->get('_route') == 'demofony2_front_participation_proposals_new',
            ));

        return $menu;
    }
}
