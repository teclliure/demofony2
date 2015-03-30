<?php
namespace Demofony2\AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Demofony2\AppBundle\Enum\ProcessParticipationStateEnum;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CitizenForumAdmin extends ProcessParticipationAdmin
{

    public function prePersist($object)
    {
        foreach ($object->getDocuments() as $document) {
            $document->setCitizenForum($object);
        }

        foreach ($object->getProposalAnswers() as $pa) {
            $pa->setCitizenForum($object);
        }

        foreach ($object->getPages() as $p) {
            $p->setCitizenForum($object);
        }
    }
}
