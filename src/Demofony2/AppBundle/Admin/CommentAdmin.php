<?php
namespace Demofony2\AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class CommentAdmin extends Admin
{
    protected $datagridValues = array(
        '_page' => 1,
        '_sort_order' => 'DESC', // sort direction
        '_sort_by' => 'publishedAt', // field name
    );

    protected function configureDatagridFilters(DatagridMapper $datagrid)
    {
        $datagrid
            ->add('title')
            ->add('revised')
            ->add('moderated')

;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('title')
            ->add('comment', 'textarea')
            ->add('revised', 'checkbox', array('required' => false))
            ->add('moderated', 'checkbox', array('required' => false))
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $mapper)
    {
        $mapper
            ->addIdentifier('title')
            ->add('createdAt')
            ->add('revised', 'boolean', array('editable' => true))
            ->add('moderated', 'boolean', array('editable' => true))
            ->add('_action', 'actions', array(
                'actions' => array(
                    'edit' => array(),
                ),
                'label' => 'Accions',
            ))
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
                'label' => $this->trans('action_revise', array(), 'SonataAdminBundle'),
                'ask_confirmation' => true,
            );
        }
        $actions['revise'] = array(
            /** @Ignore */
            'label' => $this->trans('action_revise', array(), 'SonataAdminBundle'),
            'ask_confirmation' => true,
        );

        return $actions;
    }
}
