<?php

namespace FinquesFarnos\AppBundle\Tests\Admin;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;

/**
 * Class DefaultControllerTest
 *
 * @category Test
 * @package  FinquesFarnos\AppBundle\Tests\Admin
 * @author   David Romaní <david@flux.cat>
 */
class DefaultControllerTest extends WebTestCase
{
    /**
     * Test page is successful
     *
     * @dataProvider provideUrls
     */
    public function testAdminPagesAreSuccessful($url)
    {
//        $client = $this->getAdminClient();
        $client = static::createClient();
        $client->request('GET', $url);
        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    /**
     * Get admin client
     *
     * @return Client
     */
    private function getAdminClient()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/admin/login');
        $form = $crawler->selectButton('_submit')->form(
            array(
                '_username' => 'admin',
                '_password' => 'jbMF7CZW',
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
            array('/login'),
//            array('/logout'),
//            array('/profile'), (401 unathorized)
//            array('/profile/edit'), (401 unathorized)
//            array('/register'),
        );
    }
}
