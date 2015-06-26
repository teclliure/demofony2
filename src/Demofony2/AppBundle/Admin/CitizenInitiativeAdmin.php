<?php

namespace Demofony2\AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Demofony2\AppBundle\Entity\Document;

/**
 * Class CitizenInitiativeAdmin
 *
 * @category Admin
 * @package  Demofony2\AppBundle\Admin
 */
class CitizenInitiativeAdmin extends Admin
{
    protected $translationDomain = 'admin';
    protected $baseRoutePattern = 'participation/citizen-initiative';
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
            ->addIdentifier('title', null, array('label' => 'title'))
            ->add('person', null, array('label' => 'person'))
            ->add('startAt', null, array('label' => 'startAt', 'format' => 'd-m-Y'))
            ->add('finishAt', null, array('label' => 'finishAt', 'format' => 'd-m-Y'))
            ->add('published', null, array('label' => 'published'))
            ->add(
                '_action',
                'actions',
                array(
                    'actions' => array(
                        'edit'           => array(),
                        'ShowPublicPage' => array(
                            'template' => ':Admin\Action:showPublicPage.html.twig',
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
            ->add('person', null, array('label' => 'person'));
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
                    'class'       => 'col-md-12',
                    'description' => '',
                )
            )
            ->add('published', null, array('required' => false, 'label' => 'published'))
            ->add('title', null, array('required' => true, 'label' => 'title'))
            ->add('description', 'ckeditor', array('label' => 'description', 'config' => array('height' => '450px')))
            ->add('person', 'text', array('label' => 'person'))
            ->add(
                'startAt',
                'date',
                array(
                    'label'  => 'startAt',
                    'widget' => 'single_text',
                    'format' => 'dd/MM/yyyy',
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
                    'attr'   => array('class' => 'datepicker', 'style' => 'width: 90px !important;')
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
                    'label'        => 'images',
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
                        'minWidth'    => 640,
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
                    'label'              => 'documents',
                ),
                array(
                    'edit'     => 'inline',
                    'inline'   => 'table',
                    'sortable' => 'position',
                )
            );
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
            $document->setCitizenInitiative($object);
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
