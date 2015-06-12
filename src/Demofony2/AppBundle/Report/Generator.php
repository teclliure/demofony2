<?php
/**
 * Created by PhpStorm.
 * User: marc
 * Date: 9/5/15
 * Time: 18:58
 */

namespace Demofony2\AppBundle\Report;


use Demofony2\AppBundle\Entity\ProcessParticipationBase;
use Demofony2\AppBundle\Entity\Proposal;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class Generator
 * @package Demofmony2\AppBundle\Report
 */
class Generator {

    protected $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param ProcessParticipationBase $object
     */
    public function generateProcessParticipationData(ProcessParticipationBase $object)
    {
        $data = array();
        $page1 = array();
        $page1[] = array($object->getTitle(), '');
        $page1[] = array('', '');
        $images = $object->getGallery();
//        if (count($images)) {
//            $page1[] = array($images[0], '');
//        }
        $page1[] = array($object->getDescription(), '');
        $page1[] = array('Estat:', $this->translator->trans($object->getStateName()));
        $page1[] = array('Nombre màxim de vots per usuari:', $object->getMaxVotes());
        $page1[] = array('', '');
        $page1[] = array('Data inici', $object->getDebateAt());
        $page1[] = array('', '');
        $page1[] = array('Resultats', '');
        $page1[] = array('Resposta', 'Vots');
        $answers = $object->getProposalAnswers();
        foreach ($answers as $answer) {
            $page1[] = array($answer->getTitle(), count($answer->getVotes()));
        }

        $page2 = array();
        $page2[] = array('Usuari', 'Títol', 'Comentari', 'Likes', 'Unlinke');
        $comments = $object->getComments();
        foreach ($comments as $comment)
        {
            $page2[] = array($comment->getAuthor()->__toString(), $comment->getTitle(), $comment->getComment(), $comment->getLikesCount(), $comment->getUnlikesCount());
        }
        $data['Resultats'] = $page1;
        $data['Comentaris'] = $page2;

        return $data;
    }

    /**
     * @param ProcessParticipationBase $object
     */
    public function generateCitizenForumData(ProcessParticipationBase $object)
    {
        return $this->generateProcessParticipationData($object);
    }

    /**
     * @param ProcessParticipationBase $object
     */
    public function generateProposalData(Proposal $object)
    {
        $data = array();
        $page1 = array();
        $page1[] = array($object->getTitle(), '');
        $page1[] = array('', '');
        $images = $object->getGallery();
//        if (count($images)) {
//            $page1[] = array($images[0], '');
//        }
        $page1[] = array($object->getDescription(), '');
        $page1[] = array('Estat:', $this->translator->trans($object->getStateName()));
        $page1[] = array('Nombre màxim de vots per usuari:', $object->getMaxVotes());
        $page1[] = array('', '');
        $page1[] = array('Resultats', '');
        $page1[] = array('Resposta', 'Vots');
        $answers = $object->getProposalAnswers();
        foreach ($answers as $answer) {
            $page1[] = array($answer->getTitle(), count($answer->getVotes()));
        }

        $page2 = array();
        $page2[] = array('Usuari', 'Títol', 'Comentari', 'Likes', 'Unlinke');
        $comments = $object->getComments();
        foreach ($comments as $comment)
        {
            $page2[] = array($comment->getAuthor()->__toString(), $comment->getTitle(), $comment->getComment(), $comment->getLikesCount(), $comment->getUnlikesCount());
        }
        $data['Resultats'] = $page1;
        $data['Comentaris'] = $page2;

        return $data;
    }
}