<?php

namespace Demofony2\AppBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class Demofony2AppBundle extends Bundle
{
    public function getParent()
    {
        return 'BladeTesterCalendarBundle';
    }
}
