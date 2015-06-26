<?php

namespace Demofony2\AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class InstitutionalAnswerAdmin
 *
 * @category Admin
 * @package  Demofony2\AppBundle\Admin
 */
class InstitutionalAnswerAdmin extends Admin
{
    protected $translationDomain = 'admin';
    protected $baseRoutePattern = 'no-view/institutional-answer';
    protected $datagridValues = array(
        '_page'       => 1,
        '_sort_order' => 'DESC', // sort direction
        '_sort_by'    => 'createdAt', // field name
    );

    /**
     * Configure list view
     *
     * @param ListMapper $mapper
     */
    protected function configureListFields(ListMapper $mapper)
    {
        $mapper
            ->addIdentifier('title', null, array('label' => 'title'))
            ->addIdentifier('createdAt', null, array('label' => 'createdAt'));
    }

    /**
     * Configure list view filters
     *
     * @param DatagridMapper $datagrid
     */
    protected function configureDatagridFilters(DatagridMapper $datagrid)
    {
        $datagrid
            ->add('title', null, array('label' => 'title'))
            ->add('createdAt', null, array('label' => 'createdAt'));
    }

    /**
     * Configure edit view
     *
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('title', null, array('label' => 'title', 'required' => false))
            ->add(
                'description',
                'ckeditor',
                array(
                    'label'    => 'description',
                    'required' => false,
                    'config'   => array('height' => '350px'),
                )
            );
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
