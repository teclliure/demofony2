<?php
namespace Demofony2\AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Demofony2\AppBundle\Enum\ProcessParticipationStateEnum;
use Pix\SortableBehaviorBundle\Services\PositionHandler;

class ProcessParticipationAdmin extends Admin
{

    public $last_position = 0;
    private $positionService;

    protected $datagridValues = array(
        '_page' => 1,
        '_sort_order' => 'ASC', // sort direction
        '_sort_by' => 'position', // field name
    );

    /**
     * @param PositionHandler $positionHandler
     */
    public function setPositionService(PositionHandler $positionHandler)
    {
        $this->positionService = $positionHandler;
    }

    protected function configureDatagridFilters(DatagridMapper $datagrid)
    {
        $datagrid
            ->add('title')
            ->add('state', 'doctrine_orm_callback', array(
                'callback'   => array($this, 'getStateFilter'),
                'field_type' => 'choice',
                'field_options' => array(
                    'choices' => ProcessParticipationStateEnum::getTranslations()
                )
            ))
        ;
    }


    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with(
                'General',
                array(
                    'class' => 'col-md-6',
                    'description' => 'General Information',
                )
            )
                ->add('title')
                ->add('description', 'ckeditor')
           ->end()

            ->with(
                'Controls',
                array(
                    'class' => 'col-md-6',
                    'description' => '',

                )
            )
            ->add('categories', 'sonata_type_model', array('multiple' => true, 'by_reference' => false))

            ->add('commentsModerated', 'checkbox', array('required' => false))
            ->add(
                'presentationAt',
                'sonata_type_datetime_picker',
                array('widget' => 'single_text', 'format' => 'dd/MM/yyyy HH:mm')
            )
            ->add(
                'finishAt',
                'sonata_type_datetime_picker',
                array('widget' => 'single_text', 'format' => 'dd/MM/yyyy HH:mm')
            )
            ->add(
                'debateAt',
                'sonata_type_datetime_picker',
                array('widget' => 'single_text', 'format' => 'dd/MM/yyyy HH:mm')
            )
//            ->add('state', 'choice', array('choices' => ProcessParticipationStateEnum::getTranslations()))


            ->end()

            ->with(
                'Proposal Answers',
                array(
                    'class' => 'col-md-12',
                    'description' => 'Proposal Answers',
                )
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
            ->end()
            ->with(
                'Archivos',
                array(
                    'class' => 'col-md-12',
                    'description' => '',
                )
            )

            ->add('documents', 'sonata_type_collection', array(
                'cascade_validation' => true,
            ), array(
                'edit' => 'inline',
                'inline' => 'table',
                'sortable'  => 'position',
            ))
            ->add('images', 'sonata_type_collection', array(
                'cascade_validation' => true,
            ), array(
                'edit' => 'inline',
                'inline' => 'table',
                'sortable'  => 'position',
            ))
            ->end()
            ->with(
                'Institutional Answer',
                array(
                    'class' => 'col-md-12',
                    'description' => 'Proposal Answers',
                )
            )
            ->add('institutionalAnswer', 'sonata_type_admin', array( 'btn_add' => false, 'btn_delete' => false, 'required' => false))
            ->end()
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $mapper)
    {
        $this->last_position = $this->positionService->getLastPosition($this->getRoot()->getClass());

        $mapper
            ->addIdentifier('title')
            ->add('presentationAt')
            ->add('debateAt')
            ->add('finishAt')
            ->add('state', null, array('template' => ':Admin\ListFieldTemplate:state.html.twig'))
            ->add('_action', 'actions', array(
                'actions' => array(
                    'move' => array('template' => 'PixSortableBehaviorBundle:Default:_sort.html.twig'),
                )
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
        $collection->add('move', $this->getRouterIdParameter() . '/move/{position}');
        $collection->remove('export');
    }

    public function getStateFilter($queryBuilder, $alias, $field, $value)
    {
        if (!$value['value']) {
            return;
        }

        if (ProcessParticipationStateEnum::DRAFT === $value['value']) {

            $queryBuilder->andWhere(sprintf(':now < %s.presentationAt', $alias));
            $queryBuilder->setParameter('now', new \DateTime('now'));
        }

        if (ProcessParticipationStateEnum::PRESENTATION === $value['value']) {
            $queryBuilder->andWhere(sprintf(':now > %s.presentationAt', $alias));
            $queryBuilder->andWhere(sprintf(':now < %s.debateAt', $alias));
            $queryBuilder->setParameter('now', new \DateTime('now'));
        }

        if (ProcessParticipationStateEnum::DEBATE === $value['value']) {
            $queryBuilder->andWhere(sprintf(':now > %s.presentationAt', $alias));
            $queryBuilder->andWhere(sprintf(':now > %s.debateAt', $alias));
            $queryBuilder->andWhere(sprintf(':now < %s.finishAt', $alias));
            $queryBuilder->setParameter('now', new \DateTime('now'));
        }

        if (ProcessParticipationStateEnum::CLOSED === $value['value']) {
            $queryBuilder->andWhere(sprintf(':now > %s.presentationAt', $alias));
            $queryBuilder->andWhere(sprintf(':now > %s.debateAt', $alias));
            $queryBuilder->andWhere(sprintf(':now > %s.finishAt', $alias));
            $queryBuilder->setParameter('now', new \DateTime('now'));
        }

        return true;
    }
}
