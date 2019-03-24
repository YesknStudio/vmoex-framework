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
use Yeskn\MainBundle\Entity\FooterLink;
use Yeskn\MainBundle\Twig\GlobalValue;

class StartRenderFooterLinkListEvent extends AbstractCrudListEvent
{
    /**
     * @var FooterLink[]
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
                $tag->getName(),
                $this->linkColumn($tag->getLink(), $tag->getLink()),
                $tag->getPriority(),
                $tag->isPjax() ? '是' : '否'
            ];
        }

        return [
            'columns' => ['ID', '文本', '链接', '权重','是否站内'],
            'entitySubTitle' => '可自定义网站底部的导航链接, 非站内链接将在新窗口打开。',
            'column_width' => [0 => 5],
            'list' => $result,
            'ids' => $ids
        ];
    }
}
