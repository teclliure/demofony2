<?php

namespace Demofony2\AppBundle\Form\Type\Front;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GpsFormType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//             ->add('latlng', 'oh_google_maps', array(
//                 'default_lat'    => 41.4946642,
//                 'default_lng'    => 2.3591530999999577,))
             ->add('lat','hidden',array())
             ->add('lng','hidden',array())
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Demofony2\AppBundle\Entity\Gps',
            'label_format' => 'form.label.%name%',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'demofony2_appbundle_address';
    }
}
