<?php

namespace Yeskn\UserBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    public function testLogin()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');
    }

    public function testRegister()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/register');
    }

    public function testVerifyemail()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/verify');
    }

    public function testForgot()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/forgot');
    }

}
