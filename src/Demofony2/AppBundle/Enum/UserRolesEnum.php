<?php

namespace Demofony2\AppBundle\Enum;

class UserRolesEnum extends Enum
{
    const ROLE_ADMIN       = 'ROLE_ADMIN';
    const ROLE_USER       = 'ROLE_USER';
    const ROLE_EDITOR       = 'ROLE_EDITOR';

    public static function getHumanReadableArray()
    {
        $result = [
            self::ROLE_ADMIN => 'Administrador',
            self::ROLE_USER => 'Usuari',
        ];

        return $result;
    }
}
