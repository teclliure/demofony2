<?php
namespace Demofony2\AppBundle\Enum;

class CitizenInitiativeStateEnum extends Enum
{
    const DRAFT         = 1;
    const OPEN  = 2;
    const CLOSED        = 3;

    public static function getTranslations()
    {
        return array(
            static::DRAFT =>        'citizen_initiative.state.draft',
            static::OPEN => 'citizen_initiative.state.open',
            static::CLOSED =>       'citizen_initiative.state.closed',
        );
    }
}
