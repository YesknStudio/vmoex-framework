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
    public function getVersion($path)
    {
        return '';
    }

    public function applyVersion($path)
    {
        $hash = substr(uniqid(),0, 8);
        return $path . '?v=' . $hash;
    }
}