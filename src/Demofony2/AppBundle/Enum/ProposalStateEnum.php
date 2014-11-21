<?php
namespace Demofony2\AppBundle\Enum;

class ProposalStateEnum extends Enum
{
    const DRAFT       = 0;
    const CLOSED       = 1;

    public static function getTranslations()
    {
        return array(
            static::DRAFT => 'proposal.state.draft',
            static::CLOSED => 'proposal.state.draft'
        );
    }
}
