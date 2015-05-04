<?php

namespace Demofony2\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Demofony2\AppBundle\Form\Type\Front\GpsFormType;

/**
 * Class ProfileFormType.
 *
 * @category FormType
 */
class ProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $myEntity = $builder->getForm()->getData();
        $builder
            ->add('name', null, array('label' => 'front.profile.name', 'required' => true))
            ->add('username', null, array('label' => 'front.profile.username'))
            ->add('email', 'email', array('read_only' => true, 'disabled' => true))
            ->add(
                'description',
                'textarea',
                array(
                    'required' => false,
                    'label'    => 'front.profile.description',
                    'attr'     => array(
                        'rows' => 5,
                    ),
                )
            )
            ->add(
                'image',
                'comur_image',
                array(
                    'label'        => 'image',
                    'required'     => false,
                    'uploadConfig' => array(
                        'uploadRoute'  => 'comur_api_upload',
                        'uploadUrl'    => $myEntity->getUploadRootDir(),
                        'webDir'       => $myEntity->getUploadDir(),
                        'fileExt'      => '*.jpg;*.gif;*.png;*.jpeg',
                        'libraryDir'   => null,
                        'libraryRoute' => 'comur_api_image_library',
                        'showLibrary'  => false,
                        'saveOriginal' => false,
                    ),
                    'cropConfig'   => array(
                        'minWidth'    => 263,
                        'minHeight'   => 263,
                        'aspectRatio' => true,
                        'cropRoute'   => 'comur_api_crop',
                        'forceResize' => false,
                        'thumbs'      => array(
                            array(
                                'maxWidth'        => 263,
                                'maxHeight'       => 263,
                                'useAsFieldImage' => true,
                            ),
                        ),
                    ),
                )
            )
            ->add('newsletterSubscribed', 'checkbox', array('required' => false, 'label' => 'front.profile.newsletter'))
            ->add('gps', new GpsFormType(), array())
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class'   => 'Demofony2\UserBundle\Entity\User',
                'intention'    => 'profile',
                'label_format' => 'form.label.%name%',
            )
        );
    }

    public function getName()
    {
        return 'demofony2_user_profile';
    }
}
