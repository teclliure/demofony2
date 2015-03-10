<?php
namespace Demofony2\AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Demofony2\AppBundle\Enum\ProposalStateEnum;
use Pix\SortableBehaviorBundle\Services\PositionHandler;

class ProposalAdmin extends Admin
{
    protected $datagridValues = array(
        '_page' => 1,
        '_sort_order' => 'DESC', // sort direction
        '_sort_by' => 'id', // field name
    );

    protected function configureDatagridFilters(DatagridMapper $datagrid)
    {
        $datagrid
            ->add('title')
            ->add('state', 'doctrine_orm_choice', array('choices' => ProposalStateEnum::toArray()))
            ->add('finishAt', 'doctrine_orm_datetime', array('widget' => 'single_text', 'format' => 'dd/MM/yyyy HH:mm'))
            ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('title')
            ->add('state', 'choice', array('choices' => ProposalStateEnum::getTranslations()))
            ->add('commentsModerated','checkbox', array('required' => false))
            ->add('description', 'ckeditor')
            ->add('categories', 'sonata_type_model', array('multiple' => true, 'by_reference' => false))
            ->add(
                'finishAt',
                'sonata_type_datetime_picker',
                array('widget' => 'single_text', 'format' => 'dd/MM/yyyy HH:mm')
            )
            ->add(
                'proposalAnswers',
                'sonata_type_collection',
                array(
                    'type_options' => array(
                        // Prevents the "Delete" option from being displayed
                        'delete' => true,
                        'delete_options' => array(
                            // You may otherwise choose to put the field but hide it
                            'type' => 'checkbox',
                            // In that case, you need to fill in the options as well
                            'type_options' => array(
                                'mapped' => false,
                                'required' => false,
                            ),
                        ),
                    ),
                ),
                array(
                    'edit' => 'inline',
                    'inline' => 'table',
                    'sortable' => 'position',
                )
            )
            ->add('institutionalAnswer', 'sonata_type_admin', array('delete' => false, 'btn_add' => false))

        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $mapper)
    {
        $mapper
            ->addIdentifier('title')
            ->add('finishAt')
            ->add('state')
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

    public function prePersist($object)
    {
        foreach ($object->getDocuments() as $document) {
            $document->setProposal($object);
        }

        foreach ($object->getImages() as $image) {
            $image->setProposal($object);
        }
    }

    public function preUpdate($object)
    {
        $this->prePersist($object);
    }
}
