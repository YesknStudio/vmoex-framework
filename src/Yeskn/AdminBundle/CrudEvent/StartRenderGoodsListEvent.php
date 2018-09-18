<?php

/**
 * This file is part of project wpcraft.
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
    public $entityName = '商品';

    /**
     * @var Goods[]
     */
    protected $list;

    private $router;

    private $globalValue;

    private $translator;

    private $asset;

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
                sprintf('<img width="100" src="%s" />',
                    $this->asset->getAssetUrl($tag->getCover())
                ),
                $tag->getPrice(),
                $tag->getPostFee(),
                $tag->getCount()
            ];
        }

        return [
            'columns' => ['ID', '标题', '图片', '价格', '邮费', '数量'],
            'entityName' => $this->entityName,
            'list' => $result,
            'ids' => $ids
        ];
    }
}