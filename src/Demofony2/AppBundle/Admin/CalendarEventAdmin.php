<?php

namespace Demofony2\AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CalendarEventAdmin
 *
 * @category Admin
 * @package  Demofony2\AppBundle\Admin
 */
class CalendarEventAdmin extends Admin
{
    protected $translationDomain = 'admin';
    protected $baseRoutePattern = 'participation/calendar';
    protected $datagridValues = array(
        '_page'       => 1,
        '_sort_order' => 'DESC', // sort direction
        '_sort_by'    => 'name', // field name
    );

    /**
     * Configure list view
     *
     * @param ListMapper $mapper
     */
    protected function configureListFields(ListMapper $mapper)
    {
        $mapper
            ->addIdentifier('id', null, array('label' => 'ID'))
            ->add('title', null, array('label' => 'Títol'))
            ->add(
                'start',
                null,
                array('label' => 'Data', 'template' => ':Admin\ListFieldTemplate:start-date.html.twig')
            )
            ->add('category.name', null, array('label' => 'Tipus'))
            ->add(
                '_action',
                'actions',
                array(
                    'actions' => array(
                        'edit'           => array(),
                        'ShowPublicPage' => array(
                            'template' => ':Admin\Action:showPublicCalendarEvent.html.twig',
                        ),
                    ),
                    'label'   => 'actions',
                )
            );
    }

    /**
     * Configure list view filters
     *
     * @param DatagridMapper $datagrid
     */
    protected function configureDatagridFilters(DatagridMapper $datagrid)
    {
        $datagrid
            ->add('id', null, array('label' => 'ID'))
            ->add('category.name', null, array('label' => 'Tipus'));
    }

    /**
     * Configure route collection
     *
     * @param RouteCollection $collection
     *
     * @return mixed
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('export');
        $collection->remove('delete');
        $collection->remove('show');
        $collection->remove('edit');
        $collection->remove('create');
        $collection->remove('batch');
    }

    /**
     * Set default options
     *
     * @param OptionsResolver $resolver
     */
    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'translation_domain' => 'admin',
            )
        );
    }
}
