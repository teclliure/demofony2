<?php
namespace Demofony2\AppBundle\Enum;

class ProcessParticipationStateEnum extends Enum
{
    const PRESENTATION  = 2;
    const DEBATE        = 3;
    const CLOSED        = 4;

    public static function getTranslations()
    {
        return array(
            static::PRESENTATION => 'process_participation.state.presentation',
            static::DEBATE =>       'process_participation.state.debate',
            static::CLOSED =>       'process_participation.state.closed',
        );
    }
}
