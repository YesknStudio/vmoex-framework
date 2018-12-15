<?php

/**
 * This file is part of project yeskn-studio/vmoex-framework.
 *
 * Author: Jake
 * Create: 2018-09-17 17:18:04
 */

namespace Yeskn\MainBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class StatusControllerTest extends WebTestCase
{
    /**
     * @throws \PHPUnit_Framework_Exception
     *
     * @covers \Yeskn\MainBundle\Controller\StatusController::indexAction
     * @dataProvider provideUrls
     */
    public function testStatus($url)
    {
        $client = static::createClient();

        $client->request('GET', $url);
        $client->catchExceptions(false);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $response = $client->getResponse()->getContent();

        $response = json_decode($response);

        $this->assertNotNull($response);
        $this->assertObjectHasAttribute('detail', $response);
        $this->assertEquals(1, $response->status);
    }

    public function provideUrls()
    {
        return array(
            array('/status'),
        );
    }
}
