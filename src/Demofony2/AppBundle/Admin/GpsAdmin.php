<?php

namespace Demofony2\AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class GpsAdmin
 *
 * @category Admin
 * @package  Demofony2\AppBundle\Admin
 */
class GpsAdmin extends Admin
{
    protected $translationDomain = 'admin';
    protected $baseRoutePattern = 'gps';

    /**
     * Configure list view
     *
     * @param ListMapper $mapper
     */
    protected function configureListFields(ListMapper $mapper)
    {
        $mapper
            ->addIdentifier('id')
        ;
    }

    /**
     * Configure edit view
     *
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('lat', 'text', array('required' => false))
            ->add('lng', 'text', array('required' => false))
        ;
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
