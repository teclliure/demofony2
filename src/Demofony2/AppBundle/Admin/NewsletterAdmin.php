<?php

namespace Demofony2\AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class NewsletterAdmin
 *
 * @category Admin
 * @package  Demofony2\AppBundle\Admin
 */
class NewsletterAdmin extends Admin
{
    protected $translationDomain = 'admin';
    protected $baseRoutePattern = 'newsletter';
    protected $datagridValues = array(
        '_page'       => 1,
        '_sort_order' => 'DESC', // sort direction
        '_sort_by'    => 'createdAt', // field name
    );

    /**
     * Configure list view
     *
     * @param ListMapper $mapper
     */
    protected function configureListFields(ListMapper $mapper)
    {
        $mapper
            ->addIdentifier('subject', null, array('label' => 'subject'))
            ->add('sended', null, array('label' => 'sended'))
            ->add('laws', null, array('label' => 'laws'))
            ->add(
                '_action',
                'actions',
                array(
                    'actions' => array(
                        'edit'           => array(),
                        'Preview'        => array(
                            'template' => ':Admin\Action:newsletterPreview.html.twig',
                        ),
                        'Test'           => array(
                            'template' => ':Admin\Action:newsletterTest.html.twig',
                        ),
                        'NewsletterSend' => array(
                            'template' => ':Admin\Action:newsletterSend.html.twig',
                        ),
                    ),
                    'label'   => 'actions',
                )
            );
    }

    /**
     * Configure list view filters
     *
     * @param DatagridMapper $datagrid
     */
    protected function configureDatagridFilters(DatagridMapper $datagrid)
    {
        $datagrid
            ->add('subject')
            ->add('sended');
    }

    /**
     * Configure edit view
     *
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('subject', null, array('required' => true, 'label' => 'subject'))
            ->add('description', null, array('required' => false, 'label' => 'description'))
            ->add('processParticipations', null, array('label' => 'processParticipations'))
            ->add('proposals', null, array('label' => 'proposals'))
            ->add('citizenInitiatives', null, array('label' => 'Iniciatives ciutadanes'))
            ->add('citizenForums', null, array('label' => 'Forums ciutadans'))
            ->add('documents', null, array('label' => 'documents'));
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
        $collection->add('newsletterPreview', $this->getRouterIdParameter() . '/newsletter-preview');
        $collection->add('newsletterSend', $this->getRouterIdParameter() . '/newsletter-send');
        $collection->add('newsletterTest', $this->getRouterIdParameter() . '/newsletter-test');
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
