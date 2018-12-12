<?php

/**
 * This file is part of project yeskn-studio/vmoex-framework.
 *
 * Author: Jake
 * Create: 2018-09-30 10:39:27
 */

namespace Yeskn\MainBundle\Services;

use Symfony\Component\Asset\VersionStrategy\VersionStrategyInterface;

class VersionStrategyService implements VersionStrategyInterface
{
    private $varDir;

    public function __construct($projectDir)
    {
        $this->varDir = rtrim($projectDir, '/') . '/var';
    }

    public function getVersion($path)
    {
        return '';
    }

    public function applyVersion($path)
    {
        $pathinfo = pathinfo($path);

        $ver = file_get_contents($this->varDir . '/assets_version');

        $path = $pathinfo['dirname'] . '/' .$pathinfo['filename'] . $ver . '.' . $pathinfo['extension'];

        return $path;
    }
}
