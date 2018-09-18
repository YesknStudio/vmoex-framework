<?php

/**
 * This file is part of project wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-18 19:49:13
 */

namespace Yeskn\AdminBundle\CrudEvent;

use Symfony\Bridge\Twig\Extension\AssetExtension;
use Yeskn\MainBundle\Entity\Photo;
use Yeskn\MainBundle\Twig\GlobalValue;

class StartRenderPhotoListEvent extends AbstractCrudListEvent
{
    public $entityName = '照片';

    /**
     * @var Photo[]
     */
    protected $list;

    private $asset;

    private $globalValue;

    public function __construct(GlobalValue $globalValue, AssetExtension $asset)
    {
        $this->globalValue = $globalValue;
        $this->asset = $asset;
    }

    public function execute()
    {
        $ids = $result = [];

        foreach ($this->list as $photo) {
            $ids[] = $photo->getId();

            $result[] = [
                $photo->getId(),
                $photo->getName(),
                sprintf('<img width="100" src="%s" />',
                    $this->asset->getAssetUrl($photo->getFile())
                ),
                $this->globalValue->ago($photo->getCreatedAt())
            ];
        }

        return [
            'columns' => ['ID', '名称', '文件', '发布日期'],
            'entityName' => $this->entityName,
            'list' => $result,
            'ids' => $ids,
            'extra' => [
                'columnAttr' => [
                    2 => 'align=center'
                ]
            ]
        ];
    }
}