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
use Yeskn\MainBundle\Entity\Visit;
use Yeskn\MainBundle\Twig\GlobalValue;
use Symfony\Bridge\Twig\Extension\AssetExtension;

class StartRenderVisitListEvent extends AbstractCrudListEvent
{
    /**
     * @var Visit[]
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
                $tag->getIp(),
                $tag->getPath(),
                $tag->getAgent(),
                $this->globalValue->ago($tag->getCreatedAt()),
            ];
        }

        return [
            'columns' => ['ID', 'ip', 'url', 'agent', '时间'],
            'column_width' => [0 => 5],
            'list' => $result,
            'ids' => $ids
        ];
    }
}
