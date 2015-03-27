<?php

namespace Demofony2\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Demofony2\AppBundle\Form\Type\Front\GpsFormType;

/**
 * Class ProfileFormType
 *
 * @category FormType
 * @package  Demofony2\UserBundle\Form\Type
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
                    'label' => 'front.profile.description',
                    'attr' => array(
                        'rows' => 5,
                    )
                )
            )

            ->add('name', 'text', array())
            ->add('description', 'textarea', array())
            ->add('image', 'comur_image', array(
                'label' => 'image',
                'required' => false,
                'uploadConfig' => array(
                    'uploadRoute' => 'comur_api_upload',        //optional
                    'uploadUrl' => $myEntity->getUploadRootDir(),       // required - see explanation below (you can also put just a dir path)
                    'webDir' => $myEntity->getUploadDir(),              // required - see explanation below (you can also put just a dir path)
                    'fileExt' => '*.jpg;*.gif;*.png;*.jpeg',    //optional
                    'libraryDir' => null,                       //optional
                    'showLibrary' => false,                      //optional
                ),
                'cropConfig' => array(
                    'minWidth' => 263,
                    'minHeight' => 263,
                    'aspectRatio' => true,              //optional
                    'forceResize' => false,             //optional        )
                    'thumbs' => array(                  //optional
                        array(
                            'maxWidth' => 263,
                            'maxHeight' => 263,
                            'useAsFieldImage' => true  //optional
                        )
                    )
                )
            ))
        ->add('newsletterSubscribed', 'checkbox', array('required' => false))
            ->add('gps', new GpsFormType(), array())
//            ->add('current_password', 'password', array(
//                'mapped' => false,
//                'constraints' => new UserPassword(),
//            ))
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
