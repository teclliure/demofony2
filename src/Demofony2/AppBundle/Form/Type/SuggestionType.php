<?php

namespace Demofony2\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class SuggestionType
 *
 * @category FormType
 * @package  Demofony2\AppBundle\Form\Type
 * @author   David Romaní <david@flux.cat>
 */
class SuggestionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // TODO add real name field
            ->add('title')
            ->add('theme')
            ->add('description')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Demofony2\AppBundle\Entity\Suggestion',
            'csrf_protection' => false,
            ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'suggestion';
    }
}
