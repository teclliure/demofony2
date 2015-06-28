<?php

namespace Demofony2\AppBundle\Admin;

use Demofony2\AppBundle\Enum\SuggestionSubjectEnum;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class SuggestionAdmin
 *
 * @category Admin
 * @package  Demofony2\AppBundle\Admin
 */
class SuggestionAdmin extends Admin
{
    protected $translationDomain = 'admin';
    protected $baseRoutePattern = 'system/suggestion';
    protected $datagridValues = array(
        '_page'       => 1,
        '_sort_order' => 'DESC', // sort direction
        '_sort_by'    => 'id', // field name
    );

    /**
     * Configure list view
     *
     * @param ListMapper $mapper
     */
    protected function configureListFields(ListMapper $mapper)
    {
        $mapper
            ->add('name', null, array('label' => 'name'))
            ->add('title', null, array('label' => 'title'))
            ->add(
                'subject',
                null,
                array('label' => 'subject', 'template' => ':Admin\ListFieldTemplate:subject.html.twig')
            )
            ->add('author', null, array('label' => 'Usuari'))
            ->add('email', null, array('label' => 'email'))
            ->add(
                '_action',
                'actions',
                array(
                    'actions' => array(
                        'show' => array(),
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
            ->add('name', null, array('label' => 'name'))
            ->add('author', null, array('label' => 'Usuari'))
            ->add('email', null, array('label' => 'email'))
            ->add(
                'subject',
                'doctrine_orm_choice',
                array(
                    'label'         => 'subject',
                    'field_type'    => 'choice',
                    'field_options' => array(
                        'choices' => SuggestionSubjectEnum::getTranslations(),
                    ),
                )
            );
    }

    /**
     * Configure show view
     *
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('name')
            ->add('author')
            ->add('email')
            ->add('subject')
            ->add('title')
            ->add('description', 'textarea');
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
        $collection->remove('create');
        $collection->remove('export');
        $collection->remove('edit');
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
