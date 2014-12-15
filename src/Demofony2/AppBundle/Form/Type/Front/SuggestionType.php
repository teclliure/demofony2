<?php

namespace Demofony2\AppBundle\Form\Type\Front;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormInterface;
use Demofony2\AppBundle\Enum\SuggestionSubjectEnum;

/**
 * Class SuggestionType
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
        if (!$options['isLogged']) {
            $builder
                ->add('name', 'text', array('label' => 'label.name'))
                ->add('email', 'email', array('label' => 'label.email'));
        }

        $builder
            ->add('title', 'text', array('label' => 'label.title'))
            ->add('subject', 'choice', array('choices' => SuggestionSubjectEnum::getTranslations(), 'label' => 'label.subject'))
            ->add('description', 'textarea', array('label' => 'label.description', 'attr' => array('rows' => 5)));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Demofony2\AppBundle\Entity\Suggestion',
                'csrf_protection' => true,
                'intention' => 'demofony2_suggestion',
                'isLogged' => false,
                'validation_groups' => function (FormInterface $form) {
                    $isLogged = $form->getConfig()->getOption('isLogged');
                    if (!$isLogged) {
                        return array('not_logged');
                    }
                },
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'suggestion';
    }
}
