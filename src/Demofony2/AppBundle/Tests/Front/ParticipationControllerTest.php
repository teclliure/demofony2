<?php

namespace Demofony2\AppBundle\Tests\Front;

use Liip\FunctionalTestBundle\Test\WebTestCase as WebTestCase;

/**
 * Class ParticipationControllerTest
 *
 * @category Test
 * @package  Demofony2\AppBundle\Tests\Front
 * @author   David Romaní <david@flux.cat>
 * @IgnoreAnnotation("dataProvider")
 */
class ParticipationControllerTest extends WebTestCase
{
    /**
     * Test page is successful
     *
     * @param array $url
     * @dataProvider provideUrls
     */
    public function testFrontendPagesAreSuccessful($url)
    {
        $client = static::createClient(array(), array(
                'PHP_AUTH_USER' => 'user1',
                'PHP_AUTH_PW'   => 'user1',
            ));

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
            array('/ca/participacio/'),
            array('/es/participacion/'),
            array('/en/participation/'),
//            array('/ca/participacio/calendari/'),
//            array('/es/participacion/calendario/'),
//            array('/en/participation/calendar/'),
            // processos de debat
            array('/ca/participacio/processos-de-debat/'),
            array('/es/participacion/procesos-de-debate/'),
            array('/en/participation/discussions/'),
            array('/ca/participacio/processos-de-debat/3/titol-debat-3/'),
            array('/es/participacion/procesos-de-debate/3/titol-debat-3/'),
            array('/en/participation/discussions/3/titol-debat-3/'),
            // forums ciutadans
            array('/ca/participacio/forums-ciutadans/'),
            array('/es/participacion/forums-ciudadanos/'),
            array('/en/participation/citizen-forums/'),
            array('/ca/participacio/forums-ciutadans/1/slug-1/'),
            array('/es/participacion/forums-ciudadanos/1/slug-1/'),
            array('/en/participation/citizen-forums/1/slug-1/'),
            // digues la teva
            array('/ca/participacio/propostes-ciutadanes/'),
            array('/es/participacion/propuestas-ciudadanas/'),
            array('/en/participation/proposals/'),
            array('/ca/participacio/propostes-ciutadanes/crear-proposta-nova/'),
            array('/es/participacion/propuestas-ciudadanas/crear-propuesta-nueva/'),
            array('/en/participation/proposals/add-new-proposal/'),
        );
    }
}
