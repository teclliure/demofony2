<?php

namespace Demofony2\AppBundle\Enum;

class SuggestionSubjectEnum extends Enum
{
    const TRANSPARENCY          = 1;
    const PARTICIPATION         = 2;
    const PROCESS_PARTICIPATION = 0;
    const PROPOSAL              = 10;

    public static function getTranslations()
    {
        return array(
            static::TRANSPARENCY   => 'front.home.addons.question.form.subject.choice.transparency',
            static::PARTICIPATION  => 'front.home.addons.question.form.subject.choice.participation',
//            static::PROCESS_PARTICIPATION   => 'front.home.addons.question.form.subject.choice.participation',
//            static::PROPOSAL                => 'front.home.addons.question.form.subject.choice.proposal',
            static::PROCESS_PARTICIPATION   => 'front.home.addons.question.form.subject.choice.participation',
            static::PROPOSAL                => 'front.home.addons.question.form.subject.choice.proposal',
        );
    }
}
