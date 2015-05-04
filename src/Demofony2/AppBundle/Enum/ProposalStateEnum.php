<?php

namespace Demofony2\AppBundle\Enum;

class ProposalStateEnum extends Enum
{
    const DEBATE       = 2;
    const CLOSED       = 3;

    public static function getTranslations()
    {
        return array(
            static::DEBATE => 'proposal.state.debate',
            static::CLOSED => 'proposal.state.closed',
        );
    }
}
