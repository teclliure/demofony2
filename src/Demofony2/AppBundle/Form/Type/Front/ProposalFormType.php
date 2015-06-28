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
        $myEntity = $builder->getForm()->getData();

        $builder
            ->add('title')
            ->add('description', 'textarea', array('attr' => array('rows' => 12)))
            ->add(
                'categories',
                'genemu_jqueryselect2_entity',
                array(
                    'class' => 'Demofony2AppBundle:Category',
                    'required' => false,
                    'multiple' => true,
                    'property' => 'name',
                )
            )
//            ->add('proposalAnswers')
            ->add('gps', new GpsFormType(), array())
            ->add(
                'gallery',
                'comur_gallery',
                array(
                    'required' => false,
                    'uploadConfig' => array(
                        'uploadUrl' => $myEntity->getUploadRootDir(),
                        'webDir' => $myEntity->getUploadDir(),
                        'fileExt' => '*.jpg;*.gif;*.png;*.jpeg',
                        'showLibrary' => false,
                    ),
                    'cropConfig' => array(
                        'aspectRatio' => true,
                        'minWidth' => 640,
                        'minHeight' => 480,
                        'forceResize' => false,
                    ),
                )
            )
            ->add(
                'documents',
                'collection',
                array(
                    'type' => new DocumentFormType(),
                    'allow_add' => true,
                    'allow_delete' => true, // should render default button, change text with widget_remove_btn
                    'prototype' => true,
                    'widget_add_btn' => array(
                        'label' => 'form.proposal.label.add_document',
                        'icon' => 'plus-circle',
                    ),
                    'show_legend' => false, // dont show another legend of subform
                    'options' => array(// options for collection fields
                        'label_render' => false,
                        'widget_remove_btn' => array(
                            'label' => 'form.proposal.label.remove_document',
                            'icon' => 'minus-circle',
                            'horizontal_wrapper_div' => array(
                                'class' => ""
                            )
                        ),
                    ),
                )
            )
            ->add(
                'proposalAnswers',
                'collection',
                array(
                    'type' => new ProposalAnswerFormType(),
                    'allow_add' => true,
                    'allow_delete' => true, // should render default button, change text with widget_remove_btn
                    'prototype' => true,
                    'widget_add_btn' => array(
                        'label' => 'form.proposal.label.add_proposal_answer',
                        'icon' => 'plus-circle',
                    ),
                    'show_legend' => false, // dont show another legend of subform
                    'options' => array(// options for collection fields
                        'label_render' => false,
                        'widget_remove_btn' => array(
                            'label' => 'form.proposal.label.remove_proposal_answer',
                            'icon' => 'minus-circle',
                            'horizontal_wrapper_div' => array(
                                'class' => ""
                            )
                        ),
                    ),

                )
            )
            ->add(
                'draft',
                'submit',
                array(
                    'label' => 'form.proposal.label.draft',
                    'icon' => 'fa fa-file-o',
                    'attr' => array('class' => 'btn btn-warning'),
                )
            )
            ->add(
                'send',
                'submit',
                array(
                    'label' => 'form.proposal.label.save',
                    'icon' => 'fa fa-paper-plane-o',
                    'attr' => array('class' => 'btn btn-primary'),
                )
            );
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Demofony2\AppBundle\Entity\Proposal',
                'label_format' => 'form.proposal.label.%name%',
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'demofony2_appbundle_proposal';
    }
}
