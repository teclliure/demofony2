<?php

namespace Demofony2\AppBundle\Form\Type\Api;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CommentType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('comment', 'text');

        if ('create' === $options['action']) {
            $builder->add(
                'parent',
                'entity',
                array('class' => 'Demofony2AppBundle:Comment', 'property' => 'id', 'required' => false)
            );
        }
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Demofony2\AppBundle\Entity\Comment',
                'csrf_protection' => false,
                'validation_groups' => array('create'),
                'action' => 'create',
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'comment';
    }
}
