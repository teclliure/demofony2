<?php

namespace Demofony2\AppBundle\Tests\Front;

use Liip\FunctionalTestBundle\Test\WebTestCase as WebTestCase;

/**
 * Class FrontControllerTest
 *
 * @category Test
 * @package  FinquesFarnos\AppBundle\Tests\Front
 * @author   David Romaní <david@flux.cat>
 * @IgnoreAnnotation("dataProvider")
 */
class FrontControllerTest extends WebTestCase
{
    /**
     * Test page is successful
     *
     * @dataProvider provideUrls
     */
    public function testFrontendPagesAreSuccessful($url)
    {
        $client = static::createClient();
        $client->request('GET', $url);
        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    /**
     * Urls provider
     *
     * @return array
     */
    public function provideUrls()
    {
        return array(
            array('/ca/'),
            array('/es/'),
            array('/en/'),
            array('/ca/govern/'),
            array('/es/gobierno/'),
            array('/en/government/'),
            array('/ca/participacio/'),
            array('/es/participacion/'),
            array('/en/participation/'),
            array('/ca/participacio/calendari/'),
            array('/es/participacion/calendario/'),
            array('/en/participation/calendar/'),
            array('/ca/participacio/processos-de-debat/'),
            array('/es/participacion/procesos-de-debate/'),
            array('/en/participation/discussions/'),
            array('/ca/participacio/processos-de-debat/3/titol-debat-3/'),
            array('/es/participacion/procesos-de-debate/3/titol-debat-3/'),
            array('/en/participation/discussions/3/titol-debat-3/'),
            array('/ca/participacio/propostes-ciutadanes/'),
            array('/es/participacion/propuestas-ciudadanas/'),
            array('/en/participation/proposals/'),
            array('/ca/participacio/propostes-ciutadanes/crear-proposta-nova/'),
            array('/es/participacion/propuestas-ciudadanas/crear-propuesta-nueva/'),
            array('/en/participation/proposals/add-new-proposal/'),
            array('/ca/transparencia/'),
            array('/es/transparencia/'),
            array('/en/transparency/'),
        );
    }
}
