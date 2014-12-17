<?php

namespace Demofony2\AppBundle\Form\Type\Api;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;


class CommentType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->addEventListener(
                FormEvents::PRE_SUBMIT,
                function (FormEvent $event) {
                    $data = $event->getData();
                    unset($data['_format']);
                    $event->setData($data);
                }
            )
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
        return '';
    }
}
