<?php
namespace Demofony2\AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GpsAdmin extends Admin
{

    protected $translationDomain = 'admin';

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add(
                'latlng', 'oh_google_maps', array(
                'required' => false,
                'lat_name'       => 'lat',   // the name of the lat field
                'lng_name'       => 'lng',
                'default_lat' => null,
                'default_lng' => null,
                'label' => '',
              ))
            ->add('lat', 'hidden', array('required' => false))
            ->add('lng', 'hidden', array('required' => false))
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $mapper)
    {
        $mapper
            ->addIdentifier('id')
        ;
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
