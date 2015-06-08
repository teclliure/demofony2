<?php
/**
 * Created by PhpStorm.
 * User: marc
 * Date: 9/5/15
 * Time: 19:00
 */

namespace Demofony2\AppBundle\Report;

/**
 * Interface ResponseBuilderInterface
 * @package Demofmony2\AppBundle\Report
 */
interface ResponseBuilderInterface
{
    /**
     *
     * Return a valid symfony response with generated report data
     *
     * @param array $data
     * @return boolean
     */
    public function buildResponse($data);
}