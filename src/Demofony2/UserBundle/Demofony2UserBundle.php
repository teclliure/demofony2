<?php

namespace Demofony2\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class Demofony2UserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
