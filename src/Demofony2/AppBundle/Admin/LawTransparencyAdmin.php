<?php
namespace Demofony2\AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LawTransparencyAdmin extends Admin
{
    protected $datagridValues = array(
        '_page' => 1,
        '_sort_order' => 'DESC', // sort direction
        '_sort_by' => 'name', // field name
    );

    protected $translationDomain = 'admin';

    protected function configureDatagridFilters(DatagridMapper $datagrid)
    {
        $datagrid
            ->add('name', null, array('label' => 'name'))
            ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name', null, array('label' => 'name'))
            ->add('description', 'ckeditor', array('label' => 'description'))
            ->add('links', 'sonata_type_collection', array(
                'cascade_validation' => true,
                'label' => 'links'
            ), array(
                'edit' => 'inline',
                'inline' => 'table',
                'sortable'  => 'position',
            ))
         ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $mapper)
    {
        $mapper
            ->addIdentifier('name', null, array('label' => 'name'))
            ->add('_action', 'actions', array(
                'actions' => array(
                    'edit' => array(),
                ),
                'label' => 'actions',
            ));
    }

    /**
     * Configure route collection
     *
     * @param RouteCollection $collection collection
     *
     * @return mixed
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('export');
    }


    public function prePersist($object)
    {
        foreach ($object->getLinks() as $link) {
            $link->setLaw($object);
        }
    }

    public function preUpdate($object)
    {
        $this->prePersist($object);
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
