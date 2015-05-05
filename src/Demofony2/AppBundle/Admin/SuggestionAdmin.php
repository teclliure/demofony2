<?php

namespace Demofony2\AppBundle\Admin;

use Demofony2\AppBundle\Enum\SuggestionSubjectEnum;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SuggestionAdmin extends Admin
{
    protected $datagridValues = array(
        '_page' => 1,
        '_sort_order' => 'DESC', // sort direction
        '_sort_by' => 'id', // field name
    );

    protected $translationDomain = 'admin';

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
                    'label' => 'subject',
                    'field_type' => 'choice',
                    'field_options' => array(
                        'choices' => SuggestionSubjectEnum::getTranslations(),
                    ),
                )
            );
    }

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
     * {@inheritdoc}
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
        $collection->remove('create');
        $collection->remove('export');
        $collection->remove('edit');
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
