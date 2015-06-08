<?php
/**
 * Created by PhpStorm.
 * User: marc
 * Date: 9/5/15
 * Time: 19:02
 */

namespace Demofony2\AppBundle\Report;

use Symfony\Component\HttpFoundation\Response;

class ExcelResponseBuilder implements ResponseBuilderInterface
{
    public function buildResponse($data)
    {
        $writer = new \XLSXWriter();
        $writer->setAuthor('Demofony2');

        foreach ($data as $key=>$page) {
            $writer->writeSheet($data);
        }
//        $writer->writeSheet($data2);

        return new Response(
            $writer->writeToString(),  // read from output buffer
            200,
            array(
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="report.xlsx"',
            )
        );
    }
}