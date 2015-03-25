<?php
namespace Demofony2\AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Demofony2\AppBundle\Enum\UserRolesEnum;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserAdmin extends Admin
{
    protected $datagridValues = array(
        '_page' => 1,
        '_sort_order' => 'DESC', // sort direction
        '_sort_by' => 'lastLogin', // field name
    );

    protected $translationDomain = 'admin';

    protected function configureDatagridFilters(DatagridMapper $datagrid)
    {
        $datagrid
            ->add('username', null, array('label' => 'username'))
            ->add('email', null, array('label' => 'email'))
            ->add('enabled', null, array('label' => 'enabled'))
            ->add('newsletterSubscribed', null, array('label' => 'newsletterSubscribed'));
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
                ->add('username', null, array('label' => 'username'))
                ->add('email', null, array('label' => 'email'))
                ->add('name', null, array('label' => 'name', 'required' => true))
                ->add('description', 'textarea', array('label' => 'description', 'required' => true, 'attr' => array('rows' => 6)))
//                ->add('image', 'demofony2_admin_image', array('label' => 'image', 'required' => false))
            ->add('image', 'comur_image', array(
                'label' => 'image',
                'required' => false,
                'uploadConfig' => array(
                    'uploadRoute' => 'comur_api_upload',        //optional
                    'uploadUrl' => $myEntity->getUploadRootDir(),       // required - see explanation below (you can also put just a dir path)
                    'webDir' => $myEntity->getUploadDir(),              // required - see explanation below (you can also put just a dir path)
                    'fileExt' => '*.jpg;*.gif;*.png;*.jpeg',    //optional
                    'libraryDir' => null,                       //optional
                    'showLibrary' => false,                      //optional
                ),
                'cropConfig' => array(
                    'minWidth' => 100,
                    'minHeight' => 100,
                    'aspectRatio' => true,              //optional
                    'forceResize' => false,             //optional
                )))
            ->end()
            ->with(
                'security',
                array(
                    'class' => 'col-md-6',
                    'description' => '',
                )
            )
            ->add(
                'roles',
                'choice',
                array('label' => 'roles', 'choices' => UserRolesEnum::getHumanReadableArray(), 'multiple' => true, 'expanded' => true)
            )
                ->add(
                    'plainPassword',
                    'repeated',
                    array(
                        'required' => false,
                        'type' => 'password',
                        'invalid_message' => 'passwords_not_equals',
                        'options' => array('label' => 'user.form.password'),
                        'first_options' => array('label' => 'Nova contrasenya'),
                        'second_options' => array('label' => 'Reescriu la nova contraseña'),
                    )
                )
            ->end()

            ->with(
                'controls',
                array(
                    'class' => 'col-md-6',
                    'description' => '',
                )
            )
                ->add('enabled', 'checkbox', array('label' => 'enabled', 'required' => false))
                ->add('newsletterSubscribed', 'checkbox', array('label' => 'newsletterSubscribed', 'required' => false))
            ->end()
            ->with(
                'Localització',
                array(
                    'class' => 'col-md-6',
                    'description' => '',
                )
            )
            ->add('gps', 'sonata_type_admin', array('delete' => false, 'btn_add' => false, 'label' => ' ', 'required' => false))
            ->end()


        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $mapper)
    {
        $mapper
            ->addIdentifier('username', null, array('label' => 'username'))
            ->add('email', null, array('label' => 'email'))
            ->add('createdAt', null, array('label' => 'createdAt'))
            ->add('lastLogin', null, array('label' => 'lastLogin'))
//            ->add('roles', null, array('label' => 'roles',  'template' => ':Admin\ListFieldTemplate:roles.html.twig'))
//            ->add('image', null, array('label' => 'image', 'template' => ':Admin\ListFieldTemplate:image.html.twig'))
            ->add('enabled', 'boolean', array('label' => 'enabled', 'editable' => true))
            ->add('newsletterSubscribed', 'boolean', array('label' => 'newsletterSubscribed', 'editable' => true))
            ->add('image', null, array('label' => 'image', 'template' => ':Admin\ListFieldTemplate:image.html.twig'))

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

    public function preUpdate($object)
    {
        $this->updateUser($object);
    }

    public function prePersist($object)
    {
        $this->updateUser($object);
    }

    protected function updateUser($object)
    {
        $this->getConfigurationPool()->getContainer()->get(
            'fos_user.user_manager'
        )->updateUser($object);
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
