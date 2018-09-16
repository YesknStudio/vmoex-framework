<?php

/**
 * This file is part of project wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-16 12:11:49
 */

namespace Yeskn\AdminBundle\CrudEvent;

use Symfony\Component\HttpFoundation\File\File;
use Yeskn\MainBundle\Entity\Tab;

class StartEditTabEvent implements CrudEventInterface
{
    private $tab;

    public function __construct(Tab $tab)
    {
        $this->tab = $tab;
    }

    public function execute()
    {
        $entityObj = $this->tab;

        $oldAvatar = $entityObj->getAvatar();
        $entityObj->setAvatar(new File($oldAvatar, false));

        return [
            'oldAvatar' => $oldAvatar
        ];
    }

}