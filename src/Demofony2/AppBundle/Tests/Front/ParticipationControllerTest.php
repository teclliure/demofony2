<?php

namespace Demofony2\AppBundle\Tests\Front;

use Liip\FunctionalTestBundle\Test\WebTestCase as WebTestCase;

/**
 * Class ParticipationControllerTest.
 *
 * @category Test
 *
 * @author   David Romaní <david@flux.cat>
 * @IgnoreAnnotation("dataProvider")
 */
class ParticipationControllerTest extends WebTestCase
{
    /**
     * Test page is successful.
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
     * Urls provider.
     *
     * @return array
     */
    public function provideUrls()
    {
        return array(
            array('/ca/participacio/'),
//            array('/es/participacion/'),
//            array('/en/participation/'),

            // processos de debat
            array('/ca/participacio/processos-de-debat/obert1/'),
//            array('/es/participation/discussions/open1/'),
//            array('/en/participation/discussions/open1/'),
            array('/ca/participacio/discussions/closed1/'),
//            array('/es/participation/discussions/closed1/'),
//            array('/en/participation/discussions/closed1/'),
            array('/ca/participacio/processos-de-debat/3/title-3/'),
//            array('/es/participacion/procesos-de-debate/3/title-3/'),
//            array('/en/participation/discussions/3/titol-debat-3/'),

            // forums ciutadans
            array('/ca/participacio/forums-ciutadans/'),
            array('/ca/participacio/forums-ciutadans/open1/'),
//            array('/en/participation/citizen-forums/open1/'),
//            array('/es/participation/citizen-forums/closed1/'),
            array('/ca/participacio/forums-ciutadans/closed1/'),
            array('/ca/participacio/forums-ciutada/1/fake-slug-1/'),

            // iniciatives ciutadanes
            array('/ca/participacio/iniciatives-ciutadanes/'),
            array('/ca/participacio/iniciativa-ciutadana/1/'),
//            array('/ca/participacio/iniciatives-ciutadanes/obert1'),
//            array('/ca/participacio/iniciatives-ciutadanes/tancat1'),

            // digues la teva
            array('/ca/participation/proposals/open1/'),
//            array('/es/participation/proposals/open1/'),
//            array('/en/participation/proposals/open1/'),
            array('/ca/participation/proposals/closed1/'),
//            array('/es/participation/proposals/closed1/'),
//            array('/en/participation/proposals/closed1/'),
            array('/ca/participacio/proposta-ciutadana/1/title'),
//            array('/es/participation/porposals/1/title'),
//            array('/en/participation/porposals/1/title'),
            array('/ca/participacio/proposta-ciutadana/crear-proposta-nova/'),
        );
    }
}
