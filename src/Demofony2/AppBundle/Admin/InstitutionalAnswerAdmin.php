<?php
namespace Demofony2\AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InstitutionalAnswerAdmin extends Admin
{
    protected $datagridValues = array(
        '_page' => 1,
        '_sort_order' => 'DESC', // sort direction
        '_sort_by' => 'createdAt', // field name
    );

    protected $translationDomain = 'admin';

    protected function configureDatagridFilters(DatagridMapper $datagrid)
    {
        $datagrid
            ->add('title', null, array('label' => 'title'))
            ->add('createdAt', null, array('label' => 'createdAt'))
            ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('title', null, array('label' => 'title', 'required' => false))
            ->add('description', 'ckeditor', array( 'label' => 'description', 'config' => array('height' => '350px')
            ))
//            ->add(
//                'documents',
//                'sonata_type_collection',
//                array(
//                    'type_options' => array(
//                        // Prevents the "Delete" option from being displayed
//                        'delete' => true,
//                        'delete_options' => array(
//                            // You may otherwise choose to put the field but hide it
//                            'type' => 'checkbox',
//                            // In that case, you need to fill in the options as well
//                            'type_options' => array(
//                                'mapped' => false,
//                                'required' => false,
//                            ),
//                        ),
//                    ),
//                ),
//                array(
////                    'edit' => 'inline',
////                    'inline' => 'standard',
////                    'sortable' => 'position',
//                )
//            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $mapper)
    {
        $mapper
            ->addIdentifier('title', null, array('label' => 'title'))
            ->addIdentifier('createdAt', null, array('label' => 'createdAt'))
            ;
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

    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'translation_domain' => 'admin',
            )
        );
    }
}
