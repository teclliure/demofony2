<?php

namespace Demofony2\AppBundle\Tests\Admin;

use Liip\FunctionalTestBundle\Test\WebTestCase as WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;

/**
 * Class DefaultControllerTest.
 *
 * @category Test
 * @author   David Romaní <david@flux.cat>
 * @IgnoreAnnotation("dataProvider")
 */
class DefaultControllerTest extends WebTestCase
{
    /**
     * Test page is successful
     *
     * @dataProvider provideUrls
     *
     * @param string $url
     */
    public function testAdminPagesAreSuccessful($url)
    {
        $client = $this->createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'admin',
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
            array('/admin/dashboard'),
            array('/admin/participation/category/list'),
            array('/admin/participation/category/create'),
            array('/admin/participation/category/1/edit'),
            array('/admin/participation/category/1/delete'),
            array('/admin/participation/category/1/show'),
            array('/admin/participation/participation-process/list'),
            array('/admin/participation/participation-process/create'),
            array('/admin/participation/participation-process/1/edit'),
            array('/admin/participation/participation-process/1/delete'),
            array('/admin/participation/participation-process/1/show'),
            array('/admin/participation/citizen-forum/list'),
            array('/admin/participation/citizen-forum/create'),
            array('/admin/participation/citizen-forum/1/edit'),
            array('/admin/participation/citizen-forum/1/delete'),
            array('/admin/participation/citizen-forum/1/show'),
            array('/admin/participation/proposal/list'),
            array('/admin/participation/proposal/create'),
            array('/admin/participation/proposal/1/edit'),
            array('/admin/participation/proposal/1/delete'),
            array('/admin/participation/proposal/1/show'),
            array('/admin/participation/citizen-initiative/list'),
            array('/admin/participation/citizen-initiative/create'),
            array('/admin/participation/citizen-initiative/1/edit'),
            array('/admin/participation/citizen-initiative/1/delete'),
            array('/admin/participation/citizen-initiative/1/show'),
//            array('/admin/participation/citizen-initiative/1/show-public-page'), // redirection test
//            array('/admin/participation/comment/list'), // allowed amount of queries (50) exceeded
            array('/admin/participation/comment/create'),
            array('/admin/participation/comment/1/edit'),
            array('/admin/participation/comment/1/delete'),
            array('/admin/participation/comment/1/show'),
//            array('/admin/participation/comment/1/show-public-page'), // redirection test
//            array('/admin/participation/calendar/list'),
//            array('/admin/participation/calendar/1/show'),
            array('/admin/transparency/category/list'),
            array('/admin/transparency/category/create'),
            array('/admin/transparency/category/1/edit'),
            array('/admin/transparency/category/1/delete'),
            array('/admin/transparency/category/1/show'),
            array('/admin/transparency/law/list'),
            array('/admin/transparency/law/create'),
            array('/admin/transparency/law/1/edit'),
            array('/admin/transparency/law/1/delete'),
            array('/admin/transparency/law/1/show'),
            array('/admin/transparency/document/list'),
            array('/admin/transparency/document/create'),
            array('/admin/transparency/document/1/edit'),
            array('/admin/transparency/document/1/delete'),
            array('/admin/transparency/document/1/show'),
            array('/admin/cms/page/list'),
            array('/admin/cms/page/1/edit'),
            array('/admin/cms/page/1/show'),
            array('/admin/newsletter/newsletter/list'),
            array('/admin/newsletter/newsletter/create'),
            array('/admin/system/user/list'),
            array('/admin/system/user/create'),
            array('/admin/system/user/1/edit'),
            array('/admin/system/user/1/delete'),
            array('/admin/system/user/1/show'),
//            array('/admin/system/user/1/show-public-page'),
//            array('/admin/system/suggestion/list'),
            array('/admin/system/suggestion/1/delete'),
            array('/admin/system/suggestion/1/show'),
            array('/admin/no-view/proposal-answer/list'),
            array('/admin/no-view/proposal-answer/create'),
            array('/admin/no-view/proposal-answer/1/edit'),
            array('/admin/no-view/proposal-answer/1/delete'),
            array('/admin/no-view/proposal-answer/1/show'),
            array('/admin/no-view/document/list'),
            array('/admin/no-view/document/create'),
            array('/admin/no-view/document/1/edit'),
            array('/admin/no-view/document/1/delete'),
            array('/admin/no-view/document/1/show'),
            array('/admin/no-view/image/list'),
            array('/admin/no-view/image/create'),
            array('/admin/no-view/image/1/edit'),
            array('/admin/no-view/image/1/delete'),
            array('/admin/no-view/image/1/show'),
            array('/admin/no-view/participation-process-page/list'),
            array('/admin/no-view/participation-process-page/create'),
            array('/admin/no-view/participation-process-page/1/edit'),
            array('/admin/no-view/participation-process-page/1/delete'),
            array('/admin/no-view/participation-process-page/1/show'),
            array('/admin/no-view/institutional-answer/list'),
            array('/admin/no-view/institutional-answer/create'),
            array('/admin/no-view/institutional-answer/1/edit'),
            array('/admin/no-view/institutional-answer/1/delete'),
            array('/admin/no-view/institutional-answer/1/show'),
            array('/admin/no-view/transparency-link/list'),
            array('/admin/no-view/transparency-link/create'),
            array('/admin/no-view/transparency-link/1/edit'),
            array('/admin/no-view/transparency-link/1/delete'),
            array('/admin/no-view/transparency-link/1/show'),
            array('/admin/no-view/gps/list'),
            array('/admin/no-view/gps/create'),
            array('/admin/no-view/gps/1/edit'),
            array('/admin/no-view/gps/1/delete'),
            array('/admin/no-view/gps/1/show'),
        );
    }
}
