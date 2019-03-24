<?php

/**
 * This file is part of project yeskn-studio/vmoex-framework.
 *
 * Author: Jake
 * Create: 2018-09-17 22:28:17
 */

namespace Yeskn\AdminBundle\CrudEvent;

use Symfony\Component\Routing\RouterInterface;
use Yeskn\MainBundle\Entity\Page;
use Yeskn\MainBundle\Twig\GlobalValue;

class StartRenderPageListEvent extends AbstractCrudListEvent
{
    /** @var Page[] */
    protected $list;

    private $globalValue;

    public function __construct(GlobalValue $globalValue, RouterInterface $router)
    {
        $this->globalValue = $globalValue;
        $this->router = $router;
    }

    public function execute()
    {
        $result = [];
        $ids = [];

        foreach ($this->list as $item) {
            $ids[] = $item->getId();

            $result[] = [
                $item->getId(),
                sprintf("<a href='%s'>%s</a>", $item->getUri(), $item->getTitle()),
                $item->getStatus() ? '启用' : '未启用',
                $this->globalValue->ago($item->getCreatedAt())
            ];
        }

        return [
            'columns' => ['ID', '标题', '状态', '创建时间'],
            'entitySubTitle' => '可动态为网站添加路由。',
            'list' => $result,
            'ids' => $ids
        ];
    }
}
