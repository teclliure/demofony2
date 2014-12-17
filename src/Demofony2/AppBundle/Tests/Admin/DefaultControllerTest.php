<?php

namespace Demofony2\AppBundle\Tests\Admin;

use Liip\FunctionalTestBundle\Test\WebTestCase as WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;

/**
 * Class DefaultControllerTest
 *
 * @category Test
 * @package  FinquesFarnos\AppBundle\Tests\Admin
 * @author   David Romaní <david@flux.cat>
 * @IgnoreAnnotation("dataProvider")
 */
class DefaultControllerTest extends WebTestCase
{
//    /**
//     * Test page is successful
//     *
//     * @dataProvider provideUrls
//     */
//    public function testAdminPagesAreSuccessful($url)
//    {
//        $client = $this->getAdminClient();
//        $client->request('GET', $url);
//        $this->assertTrue($client->getResponse()->isSuccessful());
//    }

    /**
     * Get admin client
     *
     * @return Client
     */
    private function getAdminClient()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/ca/');
        $form = $crawler->selectButton('_submit')->form(
            array(
                '_username' => 'admin',
                '_password' => 'admin',
            )
        );
        $client->submit($form);

        return $client;
    }

    /**
     * Urls provider
     *
     * @return array
     */
    public function provideUrls()
    {
        return array(
            array('/admin/dashboard'),
//            array('/profile'), (401 unathorized)
//            array('/profile/edit'), (401 unathorized)
//            array('/register'),
        );
    }
}
