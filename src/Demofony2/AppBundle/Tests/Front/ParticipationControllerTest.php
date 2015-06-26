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
            array('/participacio/'),

            // processos de debat
            array('/participacio/processos-de-debat/obert/1/'),
            array('/participacio/proces-de-debat/3/title-3/'),

            // forums ciutadans
            array('/participacio/forums-ciutadans/obert/1/'),
            array('/participacio/forum-ciutada/1/fake-slug-1/'),

            // iniciatives ciutadanes
            array('/participacio/iniciatives-ciutadanes/obert/1/'),

            // digues la teva
            array('/participacio/propostes-ciutadanes/obert/1/'),
            array('/participacio/proposta-ciutadana/1/title'),
            array('/participacio/proposta-ciutadana/crear-proposta-nova/'),

            // registre ciutada
            array('/participacio/registre-ciutada/'),
        );
    }
}
