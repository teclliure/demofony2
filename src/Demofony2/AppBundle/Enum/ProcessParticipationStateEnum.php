<?php
namespace Demofony2\AppBundle\Enum;

class ProcessParticipationStateEnum extends Enum
{
    const DRAFT         = 0;
    const PRESENTATION  = 1;
    const DEBATE        = 2;
    const CLOSED        = 3;

    public static function getTranslations()
    {
        return array(
            static::DRAFT =>        'process_participation.state.draft',
            static::PRESENTATION => 'process_participation.state.presentation',
            static::DEBATE =>       'process_participation.state.debate',
            static::CLOSED =>       'process_participation.state.closed',
        );
    }
}
