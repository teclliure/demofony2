<?php

namespace Demofony2\AppBundle\Enum;

class SuggestionSubjectEnum extends Enum
{
    const PROCESS_PARTICIPATION = 0;
    const PROPOSAL              = 10;

    public static function getTranslations()
    {
        return array(
            static::PROCESS_PARTICIPATION   => 'front.home.addons.question.form.subject.choice.participation',
            static::PROPOSAL                => 'front.home.addons.question.form.subject.choice.proposal',
        );
    }
}
