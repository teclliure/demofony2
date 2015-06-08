<?php
/**
 * Created by PhpStorm.
 * User: marc
 * Date: 9/5/15
 * Time: 18:58
 */

namespace Demofony2\AppBundle\Report;


use Demofony2\AppBundle\Entity\ProcessParticipationBase;

/**
 * Class Generator
 * @package Demofmony2\AppBundle\Report
 */
class Generator {
    /**
     * @param ProcessParticipationBase $object
     */
    public function generateProcessParticipationData(ProcessParticipationBase $object)
    {
        $data = array();
        $page1 = array();
        $page1[] = array($object->getTitle());
        $images = $object->getGallery();
        if (count($images)) {
            $page1[] = array($images[0]);
        }
        $page1[] = array($object->getDescription());
        $page1[] = array('Estat:', $object->getState());
        $page1[] = array('Nombre màxim de vots per usuari:', $object->getMaxVotes());
        $page1[] = array();
        $page1[] = array('Resultats');
        $page1[] = array('Resposta', 'Vots');
        $answers = $object->getProposalAnswers();
        foreach ($answers as $answer) {
            $page1[] = array($answer->getTitle(), count($answer->getVotes()));
        }
        $data['Resultats'] = $page1;

        return $data;
    }

    /**
     * @param ProcessParticipationBase $object
     */
    public function generateCitizenForumData(ProcessParticipationBase $object)
    {

    }

    /**
     * @param $data
     */
    public function buildResponse($data)
    {

    }
}