<?php
namespace Demofony2\AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Demofony2\AppBundle\Enum\ProposalStateEnum;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProposalAdmin extends Admin
{
    protected $datagridValues = array(
        '_page' => 1,
        '_sort_order' => 'DESC', // sort direction
        '_sort_by' => 'id', // field name
    );

    protected $translationDomain = 'admin';

    protected function configureDatagridFilters(DatagridMapper $datagrid)
    {
        $datagrid
            ->add('title', null, array('label' => 'title'))
            ->add('state', 'doctrine_orm_choice', array(
                'label' => 'label.state',
                'field_type' => 'choice',
                'field_options' => array(
                    'choices' => ProposalStateEnum::getTranslations(),
                ),
            ))
            ->add('pending_institutional_answer', 'doctrine_orm_callback', array(
                'label' => 'label.pending_institurional_answer',
                'callback' => function ($query, $alias, $field, $value) {
                    if (1 === $value['value']) {
                        $query->andWhere($query->getRootAliases()[0].'.finishAt < :now')
                            ->andWhere($query->getRootAliases()[0].'.institutionalAnswer is NULL')
                            ->setParameter('now', new \DateTime());
                    } elseif (0 === $value['value']) {
                        $query->join($query->getRootAliases()[0].'.institutionalAnswer', 'ia');
                    }

                    return true;
                },
                'field_type' => 'choice',
                'field_options' => array(
                    'choices' => array(0 => 'no', 1 => 'si'),
                ),            ))
//            ->add('finishAt', 'doctrine_orm_datetime_range', array(), null,  array('widget' => 'single_text', 'format' => 'dd/MM/yyyy HH:mm', 'attr' => array('class' => 'datepicker')))

            ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $myEntity = $this->getSubject();

        $formMapper
            ->with(
                'general',
                array(
                    'class' => 'col-md-6',
                    'description' => '',
                )
            )
            ->add('title', null, array('label' => 'title'))
            ->add('description', 'ckeditor', array('label' => 'description', 'config' => array('height' => '970px')))
            ->end()
            ->with(
                'controls',
                array(
                    'class' => 'col-md-6',
                    'description' => '',
                )
            )
            ->add('automaticState', null, array('label' => 'automaticState', 'required' => false, 'help' => "Actualitzar l'estat automàticament cada dia."))
            ->add('state', 'choice', array('label' => 'state', 'choices' => ProposalStateEnum::getTranslations()));

        if (true === $myEntity->getUserDraft()) {
            $formMapper->add(
                'userDraft',
                null,
                array('label' => 'userDraft', 'required' => false, 'help' => "Guardat com a borrador.")
            );
        }

        if (false === $myEntity->getUserDraft()) {
            $formMapper->add('moderated', null, array('label' => 'moderated', 'required' => false));
        }
        $formMapper->add('categories', 'sonata_type_model', array('label' => 'categories', 'multiple' => true, 'by_reference' => false))
                ->add('commentsModerated', 'checkbox', array('label' => 'commentsModerated', 'required' => false))
                ->add(
                    'finishAt',
                    'sonata_type_datetime_picker',
                    array('label' => 'finishAt', 'widget' => 'single_text', 'format' => 'dd/MM/yyyy', 'help' => 'Data a partir de la qual finalitzarà el debat.')
                )
            ->end()
            ->with(
                'Localització',
                array(
                    'class' => 'col-md-6',
                    'description' => '',
                )
            )
            ->add('gps', 'demofony2_admin_gps', array(
                /** @Ignore */
                'label' => false, ))
            ->end()
            ->with(
                'proposal_answers',
                array(
                    'class' => 'col-md-12',
                    'description' => '',
                )
            )
            ->add(
                'proposalAnswers',
                'sonata_type_collection',
                array(
                    /** @Ignore */
                    'label' => false,
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
                'files',
                array(
                    'class' => 'col-md-12',
                    'description' => '',
                )
            )

            ->add('gallery', 'comur_gallery', array(
                'label' => 'gallery',
                'required' => false,
                'uploadConfig' => array(
                    'uploadUrl' => $myEntity->getUploadRootDir(),       // required - see explanation below (you can also put just a dir path)
                    'webDir' => $myEntity->getUploadDir(),              // required - see explanation below (you can also put just a dir path)
                    'fileExt' => '*.jpg;*.gif;*.png;*.jpeg',    //optional
                    'showLibrary' => true,                      //optional
                ),
                'cropConfig' => array(
                    'aspectRatio' => true,              //optional
                    'minWidth' => 370,
                    'minHeight' => 160,
                    'forceResize' => false,             //optional
                ), ))

            ->add('documents', 'sonata_type_collection', array(
                'cascade_validation' => true,
                'label' => 'documents',
            ), array(
                'edit' => 'inline',
                'inline' => 'table',
                'sortable'  => 'position',
            ))
//            ->add('images', 'sonata_type_collection', array(
//                'cascade_validation' => true,
//                'label' => 'images',
//            ), array(
//                'edit' => 'inline',
//                'inline' => 'table',
//                'sortable'  => 'position',
//            ))

            ->end()

            ->with(
                'institutional_answer',
                array(
                    'class' => 'col-md-12',
                    'description' => '',
                )
            )
            ->add('institutionalAnswer', 'sonata_type_admin', array(
                /** @Ignore */
                'label' => false,
                'delete' => false, 'btn_add' => false, ))
            ->end()
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $mapper)
    {
        $mapper
            ->addIdentifier('title', null, array('label' => 'title'))
            ->add('finishAt', null, array('label' => 'finishAt', 'format' => 'd-m-Y'))
            ->add('state', null, array('label' => 'state', 'template' => ':Admin\ListFieldTemplate:state.html.twig'))
            ->add('automaticState', null, array('label' => 'automaticState', 'editable' => true))
            ->add('moderated', null, array('label' => 'moderated', 'editable' => true))
            ->add('_action', 'actions', array(
                'actions' => array(
                    'edit' => array(),
                ),
                'label' => 'actions',
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

    public function prePersist($object)
    {
        foreach ($object->getDocuments() as $document) {
            $document->setProposal($object);
        }

        foreach ($object->getProposalAnswers() as $pa) {
            $pa->setProposal($object);
        }
    }

    public function preUpdate($object)
    {
        $this->prePersist($object);
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
