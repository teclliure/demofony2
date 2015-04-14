<?php

namespace Demofony2\AppBundle\Form\Type\Front;

use Demofony2\AppBundle\Enum\IconEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProposalAnswerFormType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text')
//            ->add('icon', 'choice', array('label' => 'icon', 'required' => true, 'choices' => IconEnum::arrayToCss(), 'attr' => array('data-sonata-select2' => 'false', 'class' => 'select-icon')))

        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Demofony2\AppBundle\Entity\ProposalAnswer',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'demofony2_appbundle_proposalanswer';
    }
}
