<?php
/**
 * Demofony2
 * 
 * @author: Marc Morales Valldepérez <marcmorales83@gmail.com>
 * 
 * Date: 14/11/14
 * Time: 16:56
 */
namespace Demofony2\AppBundle\Datatables;

use Sg\DatatablesBundle\Datatable\View\AbstractDatatableView;

/**
 * Class UserDatatable
 *
 * @package Demofony2\UserBundle\Datatables
 */
class UserDatatable extends AbstractDatatableView
{
    /**
     * {@inheritdoc}
     */
    public function buildDatatableView()
    {
        $this->getFeatures()
            ->setAutoWidth(false)
            ->setDeferRender(true)
            ->setInfo(true)
            ->setJQueryUI(false)
            ->setLengthChange(true)
            ->setOrdering(true)
            ->setPaging(true)
            ->setProcessing(true)  // default: false
            ->setScrollX(true)     // default: false
            ->setScrollY("")
            ->setSearching(true)
            ->setServerSide(true)  // default: false
            ->setStateSave(true)
            ->setDelay(500);

        // Options (for more options see file: Sg\DatatablesBundle\Datatable\View\Options.php)
        //$this->getOptions()->setLengthMenu(array(25, 50, 100, 200, -1));
        $this->getOptions()
            ->setLengthMenu(array(25, 50, 100, 200, -1))
            ->setPageLength(25)
            ->setOrder(array("column" => 1, "direction" => "asc"));


        $this->getAjax()->setUrl($this->getRouter()->generate('demofony2_admin_user_results'));

        $this->setStyle(self::BOOTSTRAP_3_STYLE);

        $this->setIndividualFiltering(false);


        $this->getColumnBuilder()
            ->add('id', 'column', array('title' => 'Id',))
            ->add('createdAt', 'datetime', array('title' => 'CreatedAt',))
            ->add('updatedAt', 'datetime', array('title' => 'UpdatedAt',))
            ->add('removedAt', 'datetime', array('title' => 'RemovedAt',))
            ->add('name', 'column', array('title' => 'Name',))
            ->add('imageName', 'column', array('title' => 'ImageName',))
            ->add('gps.id', 'column', array('title' => 'Gps Id',))
            ->add('gps.lat', 'column', array('title' => 'Gps Lat',))
            ->add('gps.lng', 'column', array('title' => 'Gps Lng',))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return 'Demofony2\UserBundle\Entity\User';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'user_datatable';
    }
}
