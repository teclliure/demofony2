<?php

namespace Demofony2\AppBundle\Tests\Front;

use Liip\FunctionalTestBundle\Test\WebTestCase as WebTestCase;

/**
 * Class TransparencyControllerTest
 *
 * @category Test
 * @package  Demofony2\AppBundle\Tests\Front
 * @author   David Romaní <david@flux.cat>
 * @IgnoreAnnotation("dataProvider")
 */
class TransparencyControllerTest extends WebTestCase
{
    /**
     * Test page is successful
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
     * Urls provider
     *
     * @return array
     */
    public function provideUrls()
    {
        return array(
            array('/ca/transparencia/'),
            array('/es/transparencia/'),
            array('/en/transparency/'),
            array('/en/transparency/summary-account/'),
            array('/en/transparency/collaborate/'),
            array('/en/transparency/transparency-laws/'),
            array('/en/transparency/public-information/'),
            array('/ca/transparency/organitzacio-de-l-ajuntament/'),
            array('/ca/transparency/organitzacio-de-l-ajuntament/document-de-transparencia-1'),
            array('/ca/transparency/law/1'),
        );
    }
}
