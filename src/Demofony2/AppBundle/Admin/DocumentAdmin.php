<?php
namespace Demofony2\AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;

class DocumentAdmin extends Admin
{
    protected $datagridValues = array(
        '_page' => 1,
        '_sort_order' => 'DESC', // sort direction
        '_sort_by' => 'createdAt', // field name
    );

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
                ->add('document', 'file', array('required' => false, 'sonata_help' => '<p>test</p>', 'help' => '<p>test</p>'))
                ->add('documentName', 'text', array('read_only' => true, 'sonata_help' => '<p>test</p>', 'help' => '<p>test</p>'))
                ->add('position', null, array())
        ;
    }
}
