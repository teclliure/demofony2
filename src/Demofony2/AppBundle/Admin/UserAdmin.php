<?php
namespace Demofony2\AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Demofony2\AppBundle\Enum\UserRolesEnum;


class UserAdmin extends Admin
{
    protected $datagridValues = array(
        '_page' => 1,
        '_sort_order' => 'DESC', // sort direction
        '_sort_by' => 'lastLogin' // field name
    );

    protected function configureDatagridFilters(DatagridMapper $datagrid)
    {
        $datagrid
            ->add('username')
            ->add('email')
            ->add('enabled')
            ->add('createdAt')
            ->add('lastLogin');
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name')
            ->add('username')
            ->add('email')
            ->add(
                'plainPassword',
                'repeated',
                array(
                    'required' => false,
                    'type' => 'password',
                    'invalid_message' => 'Las contraseñas no son iguales',
                    'options' => array('label' => 'user.form.password'),
                    'first_options' => array('label' => 'Contraseña nueva'),
                    'second_options' => array('label' => 'Repita la contraseña nueva'),
                )
            )
            ->add(
                'roles',
                'choice',
                array('choices' => UserRolesEnum::toArray(), 'multiple' => true, 'expanded' => true)
            )
            ->add('enabled','checkbox', array('required' => false))
            ->add('image', 'file', array('required' => false));

    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $mapper)
    {
        $mapper
            ->addIdentifier('username')
            ->add('email')
            ->add('createdAt')
            ->add('lastLogin')
            ->add('roles')
            ->add('enabled', 'boolean', array('editable' => true));
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
}
