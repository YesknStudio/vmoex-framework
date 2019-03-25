<?php

/**
 * This file is part of project yeskn-studio/vmoex-framework.
 *
 * Author: Jake
 * Create: 2018-09-18 19:49:13
 */

namespace Yeskn\AdminBundle\CrudEvent;

use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Yeskn\MainBundle\Entity\Advertisement;
use Yeskn\MainBundle\Twig\GlobalValue;
use Symfony\Bridge\Twig\Extension\AssetExtension;

class StartRenderAdvertisementListEvent extends AbstractCrudListEvent
{
    /**
     * @var Advertisement[]
     */
    protected $list;

    private $globalValue;

    private $translator;

    public function __construct(RouterInterface $router, GlobalValue $globalValue, TranslatorInterface $translator
    , AssetExtension $asset){
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
                $tag->getType(),
                $tag->getLocation(),
                $tag->isEnable() ? '是' : '否',
            ];
        }

        return [
            'columns' => ['ID', '标题', '类型', '位置', '启用'],
            'column_width' => [0 => 5],
            'list' => $result,
            'ids' => $ids
        ];
    }
}
