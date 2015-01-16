<?php

namespace Demofony2\AppBundle\Form\Type\Front;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProposalFormType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description', 'textarea', array('attr' => array('rows' => 12)))
//            ->add('images')
//            ->add('documents')
            ->add('categories', 'entity', array('multiple' => true, 'class' => 'Demofony2AppBundle:Category'))
//            ->add('proposalAnswers')
            ->add('gps', new GpsFormType(), array())
            ->add('images', 'collection', array(
                'type' => new ImageFormType(),
                'allow_add' => true,
                'allow_delete' => true, // should render default button, change text with widget_remove_btn
                'prototype' => true,
                'widget_add_btn' => array('label' => "form.label.add_image", 'icon' => 'plus-square',
                ),
                'show_legend' => false, // dont show another legend of subform
                'options' => array( // options for collection fields
                    'label_render' => false,
                    'widget_remove_btn' => array('label' => "form.label.remove_image", 'icon' => 'minus-square'),
                ),
            ))
            ->add('documents', 'collection', array(
                'type' => new DocumentFormType(),
                'allow_add' => true,
                'allow_delete' => true, // should render default button, change text with widget_remove_btn
                'prototype' => true,
                'widget_add_btn' => array('label' => "form.label.add_document", 'icon' => 'plus-square',
                ),
                'show_legend' => false, // dont show another legend of subform
                'options' => array( // options for collection fields
                    'label_render' => false,
                    'widget_remove_btn' => array('label' => "form.label.remove_document", 'icon' => 'minus-square'),
                ),
            ))

            ->add('proposalAnswers', 'collection', array(
                'type' => new ProposalAnswerFormType(),
                'allow_add' => true,
                'allow_delete' => true, // should render default button, change text with widget_remove_btn
                'prototype' => true,
                'widget_add_btn' => array('label' => "form.label.add_proposal_answer", 'icon' => 'plus-square',
                ),
                'show_legend' => false, // dont show another legend of subform
                'options' => array( // options for collection fields
                    'label_render' => false,
                    'widget_remove_btn' => array('label' => "form.label.remove_proposal_answer", 'icon' => 'minus-square'),
                ),
            ))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Demofony2\AppBundle\Entity\Proposal',
            'label_format' => 'form.label.%name%',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'demofony2_appbundle_proposal';
    }
}
