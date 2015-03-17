<?php
namespace Demofony2\AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CitizenInitiativeAdmin extends Admin
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
            ->add('title')
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
                    'class' => 'col-md-12',
                    'description' => '',
                )
            )
            ->add('title', null, array('required' => true, 'label' => 'title'))
            ->add('description', 'ckeditor', array('label' => 'description'))
            ->add('person', 'text', array('label' => 'person'))
            ->add(
                'startAt',
                'sonata_type_datetime_picker',
                array('label' => 'startAt', 'widget' => 'single_text', 'format' => 'dd/MM/yyyy HH:mm')
            )
            ->add(
                'finishAt',
                'sonata_type_datetime_picker',
                array('label' => 'finishAt', 'widget' => 'single_text', 'format' => 'dd/MM/yyyy HH:mm')
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
                'uploadConfig' => array(
                    'uploadRoute' => 'comur_api_upload',        //optional
                    'uploadUrl' => $myEntity->getUploadRootDir(),       // required - see explanation below (you can also put just a dir path)
                    'webDir' => $myEntity->getUploadDir(),              // required - see explanation below (you can also put just a dir path)
                    'fileExt' => '*.jpg;*.gif;*.png;*.jpeg',    //optional
                    'libraryDir' => null,                       //optional
                    'libraryRoute' => 'comur_api_image_library', //optional
                    'showLibrary' => true,                      //optional
                    'saveOriginal' => 'originalImage'           //optional
                ),
                'cropConfig' => array(

                    'aspectRatio' => true,              //optional
                    'cropRoute' => 'comur_api_crop',    //optional
                    'forceResize' => false,             //optional
                    'thumbs' => array(                  //optional
                        array(
                            'maxWidth' => 180,
                            'maxHeight' => 400,
                            'useAsFieldImage' => true  //optional
                        )
                    )
                )            ))
            ->add('documents', 'sonata_type_collection', array(
                'cascade_validation' => true,
                'label' => 'documents',
            ), array(
                'edit' => 'inline',
                'inline' => 'table',
                'sortable'  => 'position',
            ))
            ->add('images', 'sonata_type_collection', array(
                'cascade_validation' => true,
                'label' => 'images',
            ), array(
                'edit' => 'inline',
                'inline' => 'table',
                'sortable'  => 'position',
            ))
         ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $mapper)
    {
        $mapper
            ->addIdentifier('name', null, array('label' => 'name'))
            ->add('category', null, array('label' => 'category'))
            ->add('laws', null, array('label' => 'laws'))
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
            $document->setCitizenInitiative($object);
        }

        foreach ($object->getImages() as $image) {
            $image->setCitizenInitiative($object);
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
