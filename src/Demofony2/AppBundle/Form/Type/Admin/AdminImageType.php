<?php

namespace Demofony2\AppBundle\Form\Type\Admin;

use Symfony\Component\Form\AbstractType;

class AdminImageType extends AbstractType
{

    public function getParent()
    {
        return 'file';
    }

    public function getName()
    {
        return 'demofony2_admin_image';
    }
}
