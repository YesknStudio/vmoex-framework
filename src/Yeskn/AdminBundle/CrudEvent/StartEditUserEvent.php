<?php

/**
 * This file is part of project wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-16 14:17:35
 */

namespace Yeskn\AdminBundle\CrudEvent;

use Symfony\Component\HttpFoundation\File\File;
use Yeskn\MainBundle\Entity\User;

class StartEditUserEvent implements CrudEventInterface
{
    private $entity;

    public function __construct(User $entity)
    {
        $this->entity = $entity;
    }

    public function execute()
    {
        $this->entity->setPassword('');

        $entityObj = $this->entity;

        $oldAvatar = $entityObj->getAvatar();
        $entityObj->setAvatar(new File($oldAvatar, false));

        return [
            'oldAvatar' => $oldAvatar,
            'oldPassword' => $entityObj->getPassword()
        ];
    }
}