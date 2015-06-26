<?php

namespace Demofony2\AppBundle\Admin;

use Demofony2\AppBundle\Enum\IconEnum;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Pix\SortableBehaviorBundle\Services\PositionHandler;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CategoryTransparencyAdmin
 *
 * @category Admin
 * @package  Demofony2\AppBundle\Admin
 */
class CategoryTransparencyAdmin extends Admin
{
    /** @var PositionHandler */
    private $positionService;
    public $last_position = 0;
    protected $translationDomain = 'admin';
    protected $baseRoutePattern = 'transparency/category';
    protected $datagridValues = array(
        '_page'       => 1,
        '_sort_order' => 'ASC', // sort direction
        '_sort_by'    => 'position', // field name
    );

    /**
     * @param PositionHandler $positionHandler
     */
    public function setPositionService(PositionHandler $positionHandler)
    {
        $this->positionService = $positionHandler;
    }

    /**
     * Configure list view
     *
     * @param ListMapper $mapper
     */
    protected function configureListFields(ListMapper $mapper)
    {
        $this->last_position = $this->positionService->getLastPosition($this->getRoot()->getClass());

        $mapper
            ->addIdentifier('name', null, array('label' => 'name'))
            ->add('icon', null, array('template' => ':Admin\ListFieldTemplate:icon.html.twig', 'label' => 'icon'))
            ->add('image', null, array('template' => ':Admin\ListFieldTemplate:image.html.twig', 'label' => 'image'))
            ->add(
                '_action',
                'actions',
                array(
                    'actions' => array(
                        'move' => array('template' => 'PixSortableBehaviorBundle:Default:_sort.html.twig'),
                        'edit' => array(),
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
            ->add('name', null, array('label' => 'name'));
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
            ->add('name', 'text', array('label' => 'name'))
            ->add(
                'icon',
                'choice',
                array(
                    'label'    => 'icon',
                    'required' => true,
                    'choices'  => IconEnum::arrayToCss(),
                    'attr'     => array('data-sonata-select2' => 'false', 'class' => 'select-icon')
                )
            )
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
                        'minWidth'    => 640,
                        'minHeight'   => 480,
                        'aspectRatio' => true,              //optional
                        'forceResize' => false,             //optional        )
                    ),
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
        $collection->add('move', $this->getRouterIdParameter() . '/move/{position}');
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
