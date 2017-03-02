<?php

namespace Demofony2\AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Demofony2\AppBundle\Enum\ProcessParticipationStateEnum;
use Demofony2\AppBundle\Entity\Document;
use Demofony2\AppBundle\Entity\ProposalAnswer;
use Demofony2\AppBundle\Entity\ProcessParticipationPage as Page;

/**
 * Class ProcessParticipationAdmin
 *
 * @category Admin
 * @package  Demofony2\AppBundle\Admin
 */
class ProcessParticipationAdmin extends Admin
{
    protected $translationDomain = 'admin';
    protected $baseRoutePattern = 'participation/participation-process';
    protected $datagridValues = array(
        '_page'       => 1,
        '_sort_order' => 'DESC', // sort direction
        '_sort_by'    => 'id', // field name
    );

    /**
     * Configure list view
     *
     * @param ListMapper $mapper
     */
    protected function configureListFields(ListMapper $mapper)
    {
        $mapper
            ->addIdentifier('title', null, array('label' => 'title'))
            ->add('debateAt', null, array('label' => 'debateAt', 'format' => 'd-m-Y'))
            ->add('finishAt', null, array('label' => 'finishAt', 'format' => 'd-m-Y'))
            ->add('published', null, array('label' => 'published', 'editable' => true))
            ->add('state', null, array('label' => 'state', 'template' => ':Admin\ListFieldTemplate:state.html.twig'))
            ->add(
                '_action',
                'actions',
                array(
                    'actions' => array(
                        'edit'             => array(),
                        'ShowPublicPage'   => array(
                            'template' => ':Admin\Action:showPublicPage.html.twig',
                        ),
                        'ShowResultsExcel' => array(
                            'template' => ':Admin\Action:showResultsExcel.html.twig',
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
            ->add('title', null, array('label' => 'title'))
            ->add(
                'state',
                'doctrine_orm_choice',
                array(
                    'label'         => 'state',
                    'field_type'    => 'choice',
                    'field_options' => array(
                        'choices' => ProcessParticipationStateEnum::getTranslations(),
                    ),
                )
            )
            ->add('published', null, array('label' => 'published'));
    }

    /**
     * Configure edit view
     *
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $myEntity = $this->getSubject();

        $formMapper
            ->with(
                'general',
                array(
                    'class'       => 'col-md-6',
                    'description' => '',
                )
            )
            ->add('title', null, array('label' => 'title'))
            ->add(
                'infoText',
                'ckeditor',
                array('label' => 'infoText', 'required' => false, 'config' => array('height' => '200px'))
            )
            ->add(
                'description',
                'ckeditor',
                array('label' => 'presentationDescription', 'config' => array('height' => '650px'))
            )
            ->end()
            ->with(
                'controls',
                array(
                    'class'       => 'col-md-6',
                    'description' => '',

                )
            )
            ->add(
                'published',
                null,
                array('required' => false, 'label' => 'published', 'help' => 'Publicar a la part pública')
            )
            ->add('categories', null, array('label' => 'categories', 'multiple' => true, 'by_reference' => false))
            ->add(
                'automaticState',
                null,
                array(
                    'required' => false,
                    'label'    => 'automaticState',
                    'help'     => "Si està marcat, s'actualitzarà l'estat automàticament cada dia."
                )
            )
            ->add(
                'state',
                'choice',
                array(
                    'choices'  => ProcessParticipationStateEnum::getTranslations(),
                    'required' => false,
                    'label'    => 'state'
                )
            )
            ->add(
                'commentsModerated',
                'checkbox',
                array(
                    'label'    => 'commentsModerated',
                    'required' => false,
                    'help'     => 'Els comentaris seran moderats per defecte'
                )
            )
            ->add(
                'debateAt',
                'date',
                array(
                    'label'  => 'debateAt',
                    'widget' => 'single_text',
                    'format' => 'dd/MM/yyyy',
                    'help'   => 'Data a partir de la qual es podrà comentar i votar',
                    'attr'   => array('class' => 'datepicker', 'style' => 'width: 90px !important;')
                )
            )
            ->add(
                'finishAt',
                'date',
                array(
                    'label'  => 'finishAt',
                    'widget' => 'single_text',
                    'format' => 'dd/MM/yyyy',
                    'help'   => 'Data a partir de la qual no es podrà votar ni comentar.',
                    'attr'   => array('class' => 'datepicker', 'style' => 'width: 90px !important;')

                )
            )
            ->add(
                'maxVotes',
                null,
                array('label' => 'maxVotes', 'help' => 'Màxim nombre de vots diferents per usuari.')
            )
            ->end()
            ->with(
                'Localització',
                array(
                    'class'       => 'col-md-6',
                    'description' => '',
                )
            )
            ->add(
                'gps',
                'demofony2_admin_gps',
                array(
                    'label' => '',
                )
            )
            ->end()
            ->with(
                'proposal_answers',
                array(
                    'class'       => 'col-md-12',
                    'description' => '',
                )
            )
            ->add(
                'proposalAnswers',
                'sonata_type_collection',
                array(
                    'label'        => '',
                    'type_options' => array(
                        // Prevents the "Delete" option from being displayed
                        'delete'         => true,
                        'delete_options' => array(
                            // You may otherwise choose to put the field but hide it
                            'type'         => 'checkbox',
                            // In that case, you need to fill in the options as well
                            'type_options' => array(
                                'mapped'   => false,
                                'required' => false,
                            ),
                        ),
                    ),
                ),
                array(
                    'edit'     => 'inline',
                    'inline'   => 'table',
                    'sortable' => 'position',
                )
            )
            ->end()
            ->with(
                'files',
                array(
                    'class'       => 'col-md-12',
                    'description' => '',
                )
            )
            ->add(
                'gallery',
                'comur_gallery',
                array(
                    'label'        => 'gallery',
                    'required'     => false,
                    'uploadConfig' => array(
                        'uploadUrl'   => $myEntity->getUploadRootDir(),
                        // required - see explanation below (you can also put just a dir path)
                        'webDir'      => $myEntity->getUploadDir(),
                        // required - see explanation below (you can also put just a dir path)
                        'fileExt'     => '*.jpg;*.gif;*.png;*.jpeg',
                        //optional
                        'showLibrary' => true,
                        //optional
                    ),
                    'cropConfig'   => array(
                        'aspectRatio' => true,              //optional
                        'minWidth'    => 480,
                        'minHeight'   => 480,
                        'forceResize' => false,             //optional
                    ),
                )
            )
            ->add(
                'documents',
                'sonata_type_collection',
                array(
                    'cascade_validation' => true,
                ),
                array(
                    'edit'     => 'inline',
                    'inline'   => 'table',
                    'sortable' => 'position',
                )
            )
            ->end()
            ->with(
                'pages',
                array(
                    'class' => 'col-md-12',
                )
            )
            ->add(
                'pages',
                'sonata_type_collection',
                array(
                    'cascade_validation' => true,
                    /* @Ignore */
                    'label'              => '',
                ),
                array(
                    'edit'     => 'inline',
                    'inline'   => 'table',
                    'sortable' => 'position',
                )
            )
            ->end()
            ->with(
                'institutional_answer',
                array(
                    'class' => 'col-md-12',
                )
            )
            ->add(
                'institutionalAnswer',
                'sonata_type_admin',
                array('label' => ' ', 'btn_add' => false, 'btn_delete' => false, 'required' => false)
            )
            ->add(
                'institutionalDocuments',
                'sonata_type_collection',
                array(
                    'cascade_validation' => true,
                    'label'              => 'documents',
                ),
                array(
                    'edit'     => 'inline',
                    'inline'   => 'table',
                    'sortable' => 'position',
                )
            )
            ->end();
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
        $collection->add('showPublicPage', $this->getRouterIdParameter() . '/show-public-page');
        $collection->add('showResultsExcel', $this->getRouterIdParameter() . '/show-results-excel');

        $collection->remove('export');
    }

    /**
     * Pre-persist process event
     *
     * @param mixed $object
     *
     * @return mixed
     */
    public function prePersist($object)
    {
        /** @var Document $document */
        foreach ($object->getDocuments() as $document) {
            $document->setProcessParticipation($object);
        }
        /** @var Document $document */
        foreach ($object->getInstitutionalDocuments() as $document) {
            $document->setProcessParticipationInstitutionalDocument($object);
        }
        /** @var ProposalAnswer $pa */
        foreach ($object->getProposalAnswers() as $pa) {
            $pa->setProcessParticipation($object);
        }
        /** @var Page $p */
        foreach ($object->getPages() as $p) {
            $p->setProcessParticipation($object);
        }
    }

    /**
     * Pre-update process event
     *
     * @param mixed $object
     *
     * @return mixed
     */
    public function preUpdate($object)
    {
        $this->prePersist($object);
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
