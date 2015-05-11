<?php
/**
 * Created by PhpStorm.
 * User: marc
 * Date: 9/5/15
 * Time: 19:02
 */

namespace Demofmony2\AppBundle\Report;


class ExcelResponseBuilder implements ResponseBuilderInterface
{
    public function buildResponse($data)
    {
        $writer = new XLSXWriter();
        $writer->setAuthor('Demofony2');
        $writer->writeSheet($data);
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