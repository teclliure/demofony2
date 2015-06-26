<?php

namespace Demofony2\AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class LinkTransparencyAdmin
 *
 * @category Admin
 * @package  Demofony2\AppBundle\Admin
 */
class LinkTransparencyAdmin extends Admin
{
    protected $translationDomain = 'admin';
    protected $baseRoutePattern = 'transparency/link';

    /**
     * Configure edit view
     * 
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
                ->add('name', 'text', array('label' => 'name', 'required' => false))
                ->add('url', 'url', array('label' => 'url', 'required'  => false))
                ->add('position', null, array('label' => 'position'))
        ;
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
