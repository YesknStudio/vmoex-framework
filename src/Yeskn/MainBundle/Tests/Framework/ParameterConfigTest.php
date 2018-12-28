<?php

/**
 * This file is part of project vmoex-framework.
 *
 * Author: Jake
 * Create: 2018-12-15 19:09:21
 */

namespace Yeskn\MainBundle\Tests\Framework;

use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\KernelInterface;

class ParameterConfigTest extends KernelTestCase
{
    /** @var KernelInterface */
    private $realKernel;

    protected function setUp()
    {
        $this->realKernel = static::bootKernel([]);
    }

    public function testSecret()
    {
        $container = $this->realKernel->getContainer();

        $this->assertEquals('e01b91e2f072deac5abf039b4f6e6d9149442399', $container->getParameter('secret'));
        $this->assertEquals('redis://localhost:6379', $container->getParameter('redis_dsn'));
    }
}
