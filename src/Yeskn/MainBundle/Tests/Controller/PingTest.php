<?php

/**
 * This file is part of project yeskn-studio/vmoex-framework.
 *
 * Author: Jake
 * Create: 2018-09-17 16:20:18
 */

namespace Yeskn\MainBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class PingTest
 * @package Yeskn\MainBundle\Tests\Controller
 *
 * @covers \Yeskn\MainBundle\Controller\PingController
 */
class PingTest extends WebTestCase
{
    public function testPing()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/ping');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('pong', $crawler->filter('body')->text());
    }
}
