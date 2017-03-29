<?php

namespace Demofony2\AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Demofony2\AppBundle\Enum\UserRolesEnum;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UserAdmin
 *
 * @category Admin
 * @package  Demofony2\AppBundle\Admin
 */
class UserAdmin extends Admin
{
    protected $translationDomain = 'admin';
    protected $baseRoutePattern = 'system/user';
    protected $datagridValues = array(
        '_page'       => 1,
        '_sort_order' => 'DESC', // sort direction
        '_sort_by'    => 'lastLogin', // field name
    );

    /**
     * Configure list view
     *
     * @param ListMapper $mapper
     */
    protected function configureListFields(ListMapper $mapper)
    {
        $mapper
            ->addIdentifier('username', null, array('label' => 'username'))
            ->add('email', null, array('label' => 'email'))
            ->add('createdAt', null, array('label' => 'createdAt'))
            ->add('lastLogin', null, array('label' => 'lastLogin'))
            ->add('enabled', 'boolean', array('label' => 'enabled', 'editable' => true))
            ->add('newsletterSubscribed', 'boolean', array('label' => 'newsletterSubscribed', 'editable' => true))
            ->add('image', null, array('label' => 'image', 'template' => ':Admin\ListFieldTemplate:image.html.twig'))
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
            ->add('username', null, array('label' => 'username'))
            ->add('email', null, array('label' => 'email'))
            ->add('enabled', null, array('label' => 'enabled'))
            ->add('newsletterSubscribed', null, array('label' => 'newsletterSubscribed'));
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
            ->add('username', null, array('label' => 'username'))
            ->add('email', null, array('label' => 'email'))
            ->add('name', null, array('label' => 'name', 'required' => true))
            ->add(
                'description',
                'textarea',
                array('label' => 'description', 'required' => false, 'attr' => array('rows' => 6))
            )
            ->add(
                'image',
                'comur_image',
                array(
                    'label'        => 'image',
                    'required'     => false,
                    'uploadConfig' => array(
                        'uploadRoute' => 'comur_api_upload',
                        //optional
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
                        'minWidth'    => 400,
                        'minHeight'   => 400,
                        'aspectRatio' => true,              //optional
                        'forceResize' => true,              //optional
                        'thumbs'      => array(
                            array(
                                'maxWidth'  => 200,
                                'maxHeight' => 200,
                            ),
                        ),
                    ),
                )
            )
            ->end()
            ->with(
                'security',
                array(
                    'class'       => 'col-md-6',
                    'description' => '',
                )
            )
            ->add(
                'roles',
                'choice',
                array(
                    'label'    => 'roles',
                    'choices'  => UserRolesEnum::getHumanReadableArray(),
                    'multiple' => true,
                    'expanded' => true
                )
            )
//            ->add(
//                'plainPassword',
//                'repeated',
//                array(
//                    'required'        => false,
//                    'type'            => 'password',
//                    'invalid_message' => 'passwords_not_equals',
//                    'options'         => array('label' => 'user.form.password'),
//                    'first_options'   => array('label' => 'Nova contrasenya'),
//                    'second_options'  => array('label' => 'Reescriu la nova contraseña'),
//                )
//            )
            ->add(
                'plainPassword', 'password', array(
                    'required'        => false,
                    'label' => 'user.form.password'
                )
            )
            ->end()
            ->with(
                'controls',
                array(
                    'class'       => 'col-md-6',
                    'description' => '',
                )
            )
            ->add('enabled', 'checkbox', array('label' => 'enabled', 'required' => false))
            ->add('newsletterSubscribed', 'checkbox', array('label' => 'newsletterSubscribed', 'required' => false))
            ->end()
            ->with(
                'Localització',
                array(
                    'class'       => 'col-md-12',
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
        $this->updateUser($object);
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
        $this->updateUser($object);
    }

    /**
     * Update user process
     *
     * @param $object
     */
    protected function updateUser($object)
    {
        $userManager = $this->getConfigurationPool()->getContainer()->get('fos_user.user_manager');
        $userManager->updateUser($object);
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
