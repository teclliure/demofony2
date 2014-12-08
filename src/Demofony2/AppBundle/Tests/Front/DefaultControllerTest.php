<?php

namespace Demofony2\AppBundle\Tests\Front;

use Symfony\Bundle\FrameworkBundle\Client;
use Liip\FunctionalTestBundle\Test\WebTestCase as WebTestCase;

/**
 * Class DefaultControllerTest
 *
 * @category Test
 * @package  FinquesFarnos\AppBundle\Tests\Front
 * @author   David Romaní <david@flux.cat>
 * @IgnoreAnnotation("dataProvider")
 */
class DefaultControllerTest extends WebTestCase
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
            array('/ca/transparencia/'),
            array('/es/transparencia/'),
            array('/en/transparency/'),
        );
    }
}
