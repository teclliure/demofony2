<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Demofony2\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Demofony2\AppBundle\Form\Type\Front\GpsFormType;

class ProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', null, array())
            ->add('email', 'email', array('read_only' => true, 'disabled' => true))
            ->add('name', 'text', array())
            ->add('description', 'textarea', array())
            ->add('image','file', array('required' => false))
            ->add('gps', new GpsFormType(), array())
            ->add('current_password', 'password', array(
                'mapped' => false,
                'constraints' => new UserPassword(),
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Demofony2\UserBundle\Entity\User',
            'intention'  => 'profile',
            'label_format' => 'form.label.%name%',
        ));
    }

    public function getName()
    {
        return 'demofony2_user_profile';
    }
}
