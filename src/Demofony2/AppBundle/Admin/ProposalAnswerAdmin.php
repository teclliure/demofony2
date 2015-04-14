<?php
namespace Demofony2\AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Demofony2\AppBundle\Enum\IconEnum;

class ProposalAnswerAdmin extends Admin
{
    protected $translationDomain = 'admin';

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
                ->add('title', null, array('label' => 'title'))
                ->add('icon', 'choice', array('label' => 'icon', 'required' => true, 'choices' => IconEnum::arrayToCss(), 'attr' => array('data-sonata-select2' => 'false', 'class' => 'select-icon')))
            ->add('position', null, array('label' => 'position', 'required' => false))

        ;
    }

    /**
     * Configure route collection
     *
     * @param RouteCollection $collection collection
     *
     * @return mixed
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('export');
    }

    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'translation_domain' => 'admin',
            )
        );
    }
}
