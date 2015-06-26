<?php

namespace Demofony2\AppBundle\Admin;

use Demofony2\AppBundle\Entity\Document;
use Demofony2\AppBundle\Entity\ProposalAnswer;
use Demofony2\AppBundle\Entity\ProcessParticipationPage as Page;

/**
 * Class CitizenForumAdmin
 *
 * @category Admin
 * @package  Demofony2\AppBundle\Admin
 */
class CitizenForumAdmin extends ProcessParticipationAdmin
{
    protected $translationDomain = 'admin';
    protected $baseRoutePattern = 'participation/citizen-forum';

    /**
     * Pre-persist process event
     *
     * @param mixed $object
     *
     * @return mixed
     */
    public function prePersist($object)
    {
        /** @var Document $document */
        foreach ($object->getDocuments() as $document) {
            $document->setCitizenForum($object);
        }
        /** @var Document $document */
        foreach ($object->getInstitutionalDocuments() as $document) {
            $document->setCitizenForumInstitutionalDocument($object);
        }
        /** @var ProposalAnswer $pa */
        foreach ($object->getProposalAnswers() as $pa) {
            $pa->setCitizenForum($object);
        }
        /** @var Page $p */
        foreach ($object->getPages() as $p) {
            $p->setCitizenForum($object);
        }
    }

    /**
     * Pre-update process event
     *
     * @param mixed $object
     *
     * @return mixed
     */
    public function preUpdate($object)
    {
        $this->prePersist($object);
    }
}
