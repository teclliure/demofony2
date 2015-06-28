<?php

namespace Demofony2\AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Demofony2\AppBundle\Entity\LinkTransparency;

/**
 * Class DocumentTransparencyAdmin
 *
 * @category Admin
 * @package  Demofony2\AppBundle\Admin
 */
class DocumentTransparencyAdmin extends Admin
{
    protected $translationDomain = 'admin';
    protected $baseRoutePattern = 'transparency/document';
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
            ->addIdentifier('name', null, array('label' => 'name'))
            ->add('category', null, array('label' => 'category'))
            ->add('laws', null, array('label' => 'laws'))
            ->add('position', null, array('label' => 'position', 'editable' => true))
            ->add(
                '_action',
                'actions',
                array(
                    'actions' => array(
                        'edit' => array(),
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
            ->add('category', null, array('label' => 'category'))
            ->add('laws', null, array('label' => 'laws'));
    }

    /**
     * Configure edit view
     *
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('category', null, array('required' => true, 'label' => 'category'))
            ->add('laws', null, array('label' => 'laws'))
            ->add('name', null, array('label' => 'name'))
            ->add('description', 'ckeditor', array('label' => 'description'))
            ->add('position', null, array('label' => 'position'))
            ->add(
                'links',
                'sonata_type_collection',
                array(
                    'cascade_validation' => true,
                    'label'              => 'links',
                ),
                array(
                    'edit'     => 'inline',
                    'inline'   => 'table',
                    'sortable' => 'position',
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
     * Pre-persist process event
     *
     * @param mixed $object
     *
     * @return mixed
     */
    public function prePersist($object)
    {
        /** @var LinkTransparency $link */
        foreach ($object->getLinks() as $link) {
            $link->setDocument($object);
        }
    }

    /**
     * Pre-update process event
     *
     * @param mixed $object
     *
     * @return mixed
     */
    public function preUpdate($object)
    {
        $this->prePersist($object);
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
