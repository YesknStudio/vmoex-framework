<?php

/**
 * This file is part of project yeskn-studio/vmoex-framework.
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
    /**
     * @var Photo[]
     */
    protected $list;

    protected $asset;

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
                $this->imgColumn($photo->getFile()),
                $this->globalValue->ago($photo->getCreatedAt())
            ];
        }

        return [
            'columns' => ['ID', '名称', '文件', '发布日期'],
            'entitySubTitle' => '上传图片用于在其他地方插入',
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
