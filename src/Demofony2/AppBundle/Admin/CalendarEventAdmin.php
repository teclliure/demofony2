<?php

namespace Demofony2\AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CalendarEventAdmin extends Admin
{
    protected $translationDomain = 'admin';

    protected $datagridValues = array(
        '_page' => 1,
        '_sort_order' => 'DESC', // sort direction
        '_sort_by' => 'name', // field name
    );

    protected function configureDatagridFilters(DatagridMapper $datagrid)
    {
        $datagrid
            ->add('id', null, array('label' => 'ID'));
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $mapper)
    {
        $mapper
            ->addIdentifier('id', null, array('label' => 'ID'))
//            ->addIdentifier('namename', null, array('label' => 'name'))
            ->add(
                '_action',
                'actions',
                array(
                    'actions' => array(
                    ),
                    'label' => 'actions',
                )
            );
    }

    /**
     * Configure route collection.
     *
     * @param RouteCollection $collection collection
     *
     * @return mixed
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('export');
        $collection->remove('delete');
        $collection->remove('edit');
        $collection->remove('create');
        $collection->remove('batch');
    }

    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'translation_domain' => 'admin',
            )
        );
    }
}
