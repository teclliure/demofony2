<?php
namespace Demofony2\AppBundle\Enum;

class SuggestionSubjectEnum extends Enum
{
    const PROCESS_PARTICIPATION       = 0;
    const PROPOSAL       = 10;

    public static function getTranslations()
    {
        return array(
            static::PROCESS_PARTICIPATION => 'suggestion.subject.process_participation',
            static::PROPOSAL => 'suggestion.subject.proposal',
        );
    }
}
