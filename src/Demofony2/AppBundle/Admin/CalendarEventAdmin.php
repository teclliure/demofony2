<?php

namespace Demofony2\AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Sonata\AdminBundle\Form\FormMapper;

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
        '_sort_by'    => 'title', // field name
    );

    /**
     * Configure list view
     *
     * @param ListMapper $mapper
     */
    protected function configureListFields(ListMapper $mapper)
    {
        $mapper
            ->addIdentifier('title', null, array('label' => 'Títol'))
            ->add('published', null, array('label' => 'Publicat'));
//            ->add(
//                '_action',
//                'actions',
//                array(
//                    'actions' => array(
//                        'edit'           => array(),
//                        'ShowPublicPage' => array(
//                            'template' => ':Admin\Action:showPublicCalendarEvent.html.twig',
//                        ),
//                    ),
//                    'label'   => 'actions',
//                )
//            );
    }

    /**
     * Configure list view filters
     *
     * @param DatagridMapper $datagrid
     */
    protected function configureDatagridFilters(DatagridMapper $datagrid)
    {
        $datagrid
            ->add('title', null, array('label' => 'Títol'))
            ->add('published', null, array('label' => 'Publicat'));
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
        $collection->remove('show');
    }


    /**
     * Configure edit view
     *
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        // $myEntity = $this->getSubject();

        $formMapper
            ->with(
                'general',
                array(
                    'class'       => 'col-md-8',
                    'description' => '',
                )
            )
            ->add('title', null, array('label' => 'title'))
            ->add('description', 'ckeditor', array('label' => 'description', 'config' => array('height' => '370px')))
            ->end()
            ->with(
                'controls',
                array(
                    'class'       => 'col-md-4',
                    'description' => '',
                )
            )
            ->add('published', 'choice', array('label' => 'published', 'choices' => array('Si', 'No')))
            ->end()
            ->with(
                'Subevents',
                array(
                    'class'       => 'col-md-12',
                    'description' => '',
                )
            )
            ->add(
                'subevents',
                'sonata_type_collection',
                array(
                    'cascade_validation' => true,
                    'label'              => 'Subevents',
                )
                ,array(
                    'edit'     => 'inline',
                    'inline'   => 'table'
                )
            )
            ->end();

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
