<?php

namespace Demofony2\AppBundle\Tests\Front;

use Liip\FunctionalTestBundle\Test\WebTestCase as WebTestCase;

/**
 * Class TransparencyControllerTest.
 *
 * @category Test
 *
 * @author   David Romaní <david@flux.cat>
 * @IgnoreAnnotation("dataProvider")
 */
class TransparencyControllerTest extends WebTestCase
{
    /**
     * Test page is successful.
     *
     * @param array $url
     * @dataProvider provideUrls
     */
    public function testTransparencyPagesAreSuccessful($url)
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
            array('/ca/transparencia/'),
            array('/ca/transparencia/rendicio-de-comptes/'),
            array('/ca/transparencia/col-labora/'),
            array('/ca/transparencia/lleis-i-referencies/'),
            array('/ca/transparencia/acces-a-la-informacio-publica/'),
            array('/ca/transparencia/organitzacio-de-l-ajuntament/'),
            array('/ca/transparencia/organitzacio-de-l-ajuntament/document-de-transparencia-1'),
            array('/ca/transparencia/llei/1/'),
        );
    }
}
