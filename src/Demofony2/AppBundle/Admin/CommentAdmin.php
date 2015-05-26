<?php

namespace Demofony2\AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentAdmin extends Admin
{
    protected $datagridValues = array(
        '_page' => 1,
        '_sort_order' => 'DESC', // sort direction
        '_sort_by' => 'createdAt', // field name
    );

    protected $translationDomain = 'admin';

    protected function configureDatagridFilters(DatagridMapper $datagrid)
    {
        $datagrid
            ->add('title', null, array('label' => 'title'))
            ->add('revised', null, array('label' => 'revised'))
            ->add('moderated', null, array('label' => 'moderated'))
            ->add('processParticipation', null, array('label' => 'Processos de debat'))
            ->add('proposal', null, array('label' => 'Digues la teva'))
            ->add('citizenForum', null, array('label' => 'Fòrums ciutadans'));
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('title', null, array('label' => 'title'))
            ->add('comment', 'textarea', array('label' => 'comment'))
            ->add('revised', 'checkbox', array('required' => false, 'label' => 'revised'))
            ->add('moderated', 'checkbox', array('required' => false, 'label' => 'moderated'));
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $mapper)
    {
        $mapper
            ->addIdentifier('title', null, array('label' => 'title'))
            ->add('origin', null, array('label' => 'Origen', 'template' => ':Admin\ListFieldTemplate:origin.html.twig'))
            ->add('createdAt', null, array('label' => 'createdAt', 'format' => 'd-m-Y h:i'))
            ->add('revised', 'boolean', array('editable' => true, 'label' => 'revised'))
            ->add('moderated', 'boolean', array('editable' => true, 'label' => 'moderated'))
            ->add(
                '_action',
                'actions',
                array(
                    'actions' => array(
                        'edit' => array(),
                        'ShowPublicPage' => array(
                            'template' => ':Admin\Action:showPublicPage.html.twig',
                        ),
                    ),
                    'label' => 'actions',
                )
            );
    }

    /**
     * Configure route collection.
     *
     * @param RouteCollection $collection collection
     *
     * @return mixed
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('showPublicPage', $this->getRouterIdParameter().'/show-public-page');
        $collection->remove('export');
    }

    public function getBatchActions()
    {
        // retrieve the default (currently only the delete action) actions
        $actions = parent::getBatchActions();

        if (
            $this->hasRoute('edit') && $this->isGranted('EDIT') &&
            $this->hasRoute('delete') && $this->isGranted('DELETE')
        ) {
            $actions['revise'] = array(
                /** @Ignore */
                'label' => $this->trans('action_revise', array(), 'admin'),
                'ask_confirmation' => true,
            );
        }
        $actions['revise'] = array(
            /** @Ignore */
            'label' => $this->trans('action_revise', array(), 'admin'),
            'ask_confirmation' => true,
        );

        return $actions;
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
