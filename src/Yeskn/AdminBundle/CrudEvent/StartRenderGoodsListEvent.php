<?php

/**
 * This file is part of project yeskn-studio/vmoex-framework.
 *
 * Author: Jake
 * Create: 2018-09-18 19:49:13
 */

namespace Yeskn\AdminBundle\CrudEvent;

use Symfony\Bridge\Twig\Extension\AssetExtension;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Yeskn\MainBundle\Entity\Goods;
use Yeskn\MainBundle\Twig\GlobalValue;

class StartRenderGoodsListEvent extends AbstractCrudListEvent
{
    /**
     * @var Goods[]
     */
    protected $list;

    private $globalValue;

    private $translator;

    public function __construct(RouterInterface $router, GlobalValue $globalValue, TranslatorInterface $translator, AssetExtension $asset)
    {
        $this->router = $router;
        $this->globalValue = $globalValue;
        $this->translator = $translator;
        $this->asset = $asset;
    }

    public function execute()
    {
        $ids = $result = [];

        foreach ($this->list as $tag) {
            $ids[] = $tag->getId();

            $result[] = [
                $tag->getId(),
                $tag->getTitle(),
                $this->imgColumn($this->asset->getAssetUrl($tag->getCover())),
                $tag->getPrice(),
                $tag->getPostFee(),
                $tag->getCount()
            ];
        }

        return [
            'columns' => ['ID', '标题', '图片', '价格', '邮费', '数量'],
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
