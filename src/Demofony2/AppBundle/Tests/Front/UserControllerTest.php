<?php

namespace Demofony2\AppBundle\Tests\Front;

use Liip\FunctionalTestBundle\Test\WebTestCase as WebTestCase;

/**
 * Class UserControllerTest.
 *
 * @category Test
 *
 * @author   David Romaní <david@flux.cat>
 * @IgnoreAnnotation("dataProvider")
 */
class UserControllerTest extends WebTestCase
{
    /**
     * Test page is successful.
     *
     * @param array $url
     * @dataProvider provideUrls
     */
    public function testFrontendPagesAreSuccessful($url)
    {
        $client = static::createClient();
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
            array('/login'),
            array('/perfil/user1/comentaris/1/'),
            array('/perfil/user1/propostes/1/'),
            array('/contrasenya-oblidada/peticio'),
        );
    }

    /**
     * Test logged user page is successful.
     *
     * @param array $url
     * @dataProvider loggedUserProvideUrls
     */
    public function testLoggedUserFrontendPagesAreSuccessful($url)
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
    public function loggedUserProvideUrls()
    {
        return array(
            array('/perfil/editar'),
            array('/perfil/user1/canviar-contrasenya/'),
//            array('/logout'),
        );
    }
}
