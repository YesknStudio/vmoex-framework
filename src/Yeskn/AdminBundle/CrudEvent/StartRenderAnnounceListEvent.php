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
use Yeskn\MainBundle\Entity\Announce;
use Yeskn\MainBundle\Twig\GlobalValue;

class StartRenderAnnounceListEvent extends AbstractCrudListEvent
{
    /**
     * @var Announce[]
     */
    protected $list;

    private $globalValue;

    private $translator;

    public function __construct(RouterInterface $router, GlobalValue $globalValue, TranslatorInterface $translator)
    {
        $this->router = $router;
        $this->globalValue = $globalValue;
        $this->translator = $translator;
    }

    public function execute()
    {
        $ids = $result = [];

        foreach ($this->list as $tag) {
            $ids[] = $tag->getId();

            $result[] = [
                $tag->getId(),
                $tag->getContent(),
                $tag->isShow() ? '启用' : '不启用',
                $this->globalValue->ago($tag->getCreatedAt()),
                $this->globalValue->ago($tag->getUpdatedAt())
            ];
        }

        return [
            'columns' => ['ID', '内容', '状态', '创建时间', '更新时间'],
            'list' => $result,
            'ids' => $ids
        ];
    }
}
