<?php

namespace Demofony2\AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class DocumentAdmin
 *
 * @category Admin
 * @package  Demofony2\AppBundle\Admin
 */
class DocumentAdmin extends Admin
{
    protected $translationDomain = 'admin';
    protected $baseRoutePattern = 'no-view/document';
    protected $datagridValues = array(
        '_page'       => 1,
        '_sort_order' => 'DESC', // sort direction
        '_sort_by'    => 'createdAt', // field name
    );

    /**
     * Configure edit view
     *
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('document', 'demofony2_admin_document', array('required' => false, 'label' => 'document'))
            ->add(
                'name',
                'text',
                array(
                    'label'       => 'file_name',
                    'sonata_help' => 'Nom del fitxer que es descarregarà.',
                    'required'    => false
                )
            )
            ->add('position', null, array('label' => 'position'));
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
