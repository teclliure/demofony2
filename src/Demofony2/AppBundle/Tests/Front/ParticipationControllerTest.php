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

            // processos de debat
            array('/ca/participacio/processos-de-debat/obert1/'),
            array('/ca/participacio/proces-de-debat/3/title-3/'),

            // forums ciutadans
            array('/ca/participacio/forums-ciutadans/obert1/'),
            array('/ca/participacio/forum-ciutada/1/fake-slug-1/'),

            // iniciatives ciutadanes
            array('/ca/participacio/iniciatives-ciutadanes/obert1/'),

            // digues la teva
            array('/ca/participacio/propostes-ciutadanes/obert1/'),
            array('/ca/participacio/proposta-ciutadana/1/title'),
            array('/ca/participacio/proposta-ciutadana/crear-proposta-nova/'),
        );
    }
}
