<?php

namespace Demofony2\AppBundle\Tests\Front;

use Liip\FunctionalTestBundle\Test\WebTestCase as WebTestCase;

/**
 * Class FrontControllerTest
 *
 * @category Test
 * @package  Demofony2\AppBundle\Tests\Front
 * @author   David Romaní <david@flux.cat>
 * @IgnoreAnnotation("dataProvider")
 */
class FrontControllerTest extends WebTestCase
{
    /**
     * Test page is successful
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
            array('/ca/open-government/'),
            array('/es/open-government/'),
            array('/en/open-government/'),
        );
    }
}
