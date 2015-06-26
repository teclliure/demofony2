<?php

namespace Demofony2\AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ImageAdmin
 *
 * @category Admin
 * @package  Demofony2\AppBundle\Admin
 */
class ImageAdmin extends Admin
{
    protected $translationDomain = 'admin';
    protected $baseRoutePattern = 'no-view/image';
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
            ->addIdentifier('imageName')
            ->add('position', null, array('label' => 'position', 'editable' => true));
    }

    /**
     * Configure edit view
     *
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('image', 'demofony2_admin_image', array('label' => 'image', 'required' => false))
            ->add('position', null, array('label' => 'position', 'required' => false));
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
