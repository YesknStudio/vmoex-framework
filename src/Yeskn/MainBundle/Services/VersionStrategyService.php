<?php

/**
 * This file is part of project wpcraft.
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
        $this->varDir = rtrim($projectDir, '/') . '/assets_version';
    }

    public function getVersion($path)
    {
        return '';
    }

    public function applyVersion($path)
    {
        return $path . '?v=' . file_get_contents($this->varDir);
    }
}