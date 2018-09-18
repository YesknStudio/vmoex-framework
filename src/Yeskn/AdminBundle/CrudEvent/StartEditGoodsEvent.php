<?php

/**
 * This file is part of project wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-17 20:14:19
 */

namespace Yeskn\AdminBundle\CrudEvent;

use Symfony\Component\HttpFoundation\File\File;
use Yeskn\MainBundle\Entity\Goods;

class StartEditGoodsEvent extends AbstractCrudEntityEvent
{
    /**
     * @var Goods
     */
    protected $entity;

    public static $oldProperty;

    public function execute()
    {
        $entityObj = $this->entity;

        if ($oldCover = $entityObj->getCover()) {
            $entityObj->setCover(new File($oldCover, false));
        } else {
            $oldCover = null;
        }

        return self::$oldProperty = [
            'cover' => $oldCover
        ];
    }
}