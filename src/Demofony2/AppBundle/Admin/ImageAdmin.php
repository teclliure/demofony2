<?php
namespace Demofony2\AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;

class ImageAdmin extends Admin
{
    protected $datagridValues = array(
        '_page' => 1,
        '_sort_order' => 'DESC', // sort direction
        '_sort_by' => 'createdAt', // field name
    );

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
                ->add('image', 'file', array('label' => 'test', 'required' => false, 'sonata_help' => '<p>test</p>', 'help' => '<p>test</p>'))
//                ->add('preview', 'text', array('mapped' => false, 'sonata_help' => $this->getImageThumbnail('image'), 'help' => $this->getImageThumbnail('image')))
                ->add('position', null, array('required' => false,  'sonata_help' => $this->getImageThumbnail('image')))
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $mapper)
    {
        $mapper
            ->addIdentifier('imageName')
            ->add('position', null, array('label' => 'Posició', 'editable' => true))
        ;
    }

    public function getImageThumbnail($mapping)
    {
        $vich = $this->getConfigurationPool()->getContainer()->get('vich_uploader.templating.helper.uploader_helper');
        $object = $this->getSubject();

        if (is_object($object) && null !== $object->getImageName()) {
            $path = $vich->asset($object, $mapping);
            $imgHtml = '<img src="'.$path. '" width=300>';

            return $imgHtml;
        }

        return  '<p>hola</p>';
    }
}
