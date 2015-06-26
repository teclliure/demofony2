<?php

namespace Demofony2\AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Demofony2\AppBundle\Enum\IconEnum;

/**
 * Class ProposalAnswerAdmin
 *
 * @category Admin
 * @package  Demofony2\AppBundle\Admin
 */
class ProposalAnswerAdmin extends Admin
{
    protected $translationDomain = 'admin';
    protected $baseRoutePattern = 'no-view/proposal-answer';

    /**
     * Configure edit view
     *
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('title', null, array('label' => 'title'))
            ->add(
                'icon',
                'choice',
                array(
                    'label' => 'icon',
                    'required' => true,
                    'choices' => IconEnum::arrayToCss(),
                    'attr' => array('data-sonata-select2' => 'false', 'class' => 'select-icon')
                )
            )
            ->add('position', null, array('label' => 'position', 'required' => false));
    }

    /**
     * Configure route collection
     *
     * @param RouteCollection $collection
     *
     * @return mixed
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('export');
    }

    /**
     * Set default options
     *
     * @param OptionsResolver $resolver
     */
    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'translation_domain' => 'admin',
            )
        );
    }
}
