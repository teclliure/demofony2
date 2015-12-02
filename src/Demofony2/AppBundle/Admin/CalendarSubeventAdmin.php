<?php

namespace Demofony2\AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * Class CalendarSubventAdmin
 *
 * @category Admin
 * @package  Demofony2\AppBundle\Admin
 */
class CalendarSubeventAdmin extends Admin
{
    protected $translationDomain = 'admin';
    /**
     * Configure list view
     *
     * @param ListMapper $mapper
     */
    protected function configureListFields(ListMapper $mapper)
    {
        $mapper
            ->addIdentifier('id', null, array('label' => 'ID'))
            ->add('title', null, array('label' => 'Títol'));
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
            ->add('title', null, array('label' => 'Títol'));
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
        $collection->remove('batch');
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
            ->add('title', null, array('label' => 'title'))
            ->add(
                'startAt',
                'date',
                array(
                    'label'  => 'startAt',
                    'widget' => 'single_text',
                    'format' => 'dd/MM/yyyy',
                    'help'   => 'Data inici',
                    'attr'   => array('class' => 'datepicker', 'style' => 'width: 108px !important;')
                )
            )
            ->add(
                'finishAt',
                'date',
                array(
                    'label'  => 'finishAt',
                    'widget' => 'single_text',
                    'format' => 'dd/MM/yyyy',
                    'help'   => 'Data finalització',
                    'attr'   => array('class' => 'datepicker', 'style' => 'width: 108px !important;')
                )
            )
            ->add('location')
            ->add('color', 'text',
                array(
                    'attr' => array('class' => 'colorpicker', 'style' => 'width: 40px !important;')
                )
            )
            ->add('description', 'ckeditor', array('label' => 'description', 'config' => array('height' => '200px', 'widht' => '400px')))
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
