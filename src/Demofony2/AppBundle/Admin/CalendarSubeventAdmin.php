<?php

namespace Demofony2\AppBundle\Admin;

use Demofony2\AppBundle\Entity\CalendarSubevent;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * Class CalendarSubventAdmin
 *
 * @category Admin
 * @package  Demofony2\AppBundle\Admin
 */
class CalendarSubeventAdmin extends Admin
{
    protected $translationDomain = 'admin';
    /**
     * Configure list view
     *
     * @param ListMapper $mapper
     */
    protected function configureListFields(ListMapper $mapper)
    {
        $mapper
            ->addIdentifier('id', null, array('label' => 'ID'))
            ->add('title', null, array('label' => 'Títol'));
    }

    /**
     * Configure list view filters
     *
     * @param DatagridMapper $datagrid
     */
    protected function configureDatagridFilters(DatagridMapper $datagrid)
    {
        $datagrid
            ->add('id', null, array('label' => 'ID'))
            ->add('title', null, array('label' => 'Títol'));
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
        $collection->remove('show');
        $collection->remove('batch');
    }


    /**
     * Configure edit view
     *
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $myEntity = $this->getSubject();
        if (!$myEntity) {
            $myEntity = new CalendarSubevent();
        }

        $formMapper
            ->add('title', null, array('label' => 'title'))
            ->add(
                'startAt',
                'sonata_type_datetime_picker',
                array(
                    'label'  => 'Inici',
                    'format' => 'dd/MM/yyyy HH:mm',
                    'dp_language' => 'ca',
                    'help'   => 'Data inici',
                    'attr'   => array(
                        'class' => 'datetimepicker',
                        'style' => 'width: 125px !important;',
                        'data-date-format' => 'DD/MM/YYYY HH:mm'
                    )
                )
            )
            ->add(
                'finishAt',
                'sonata_type_datetime_picker',
                // 'datetime',
                array(
                    'label'  => 'Final',
                    'format' => 'dd/MM/yyyy HH:mm',
                    'dp_language' => 'ca',
                    'help'   => 'Data finalització',
                    'attr'   => array(
                        'class' => 'datetimepicker',
                        'style' => 'width: 125px !important;',
                        'data-date-format' => 'DD/MM/YYYY HH:mm'
                    )
                )
            )
            ->add('location')
            ->add(
                'image',
                'comur_image',
                array(
                    'label'        => 'image',
                    'required'     => false,
                    'uploadConfig' => array(
                        'uploadUrl'   => $myEntity->getUploadRootDir(),
                        // required - see explanation below (you can also put just a dir path)
                        'webDir'      => $myEntity->getUploadDir(),
                        // required - see explanation below (you can also put just a dir path)
                        'fileExt'     => '*.jpg;*.gif;*.png;*.jpeg',
                        //optional
                        'libraryDir'  => null,
                        //optional
                        'showLibrary' => false,
                        //optional
                    ),
                    'cropConfig'   => array(
                        'minWidth'    => 200,
                        'minHeight'   => 150,
                        'aspectRatio' => true,              //optional
                        'forceResize' => false,             //optional        )
                    ),
                )
            )
            ->add('color', 'text',
                array(
                    'attr' => array('class' => 'colorpicker', 'style' => 'width: 40px !important;')
                )
            )
            ->add('description', 'ckeditor', array('label' => 'description', 'config' => array('height' => '200px', 'widht' => '300px')))
            ->end();
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
