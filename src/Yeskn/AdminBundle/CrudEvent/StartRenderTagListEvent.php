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
use Yeskn\MainBundle\Entity\Tag;
use Yeskn\MainBundle\Twig\GlobalValue;

class StartRenderTagListEvent extends AbstractCrudListEvent
{
    public $entityName = '标签';

    /**
     * @var Tag[]
     */
    protected $list;

    private $router;

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
                $tag->getSlug(),
                $this->globalValue->ago($tag->getCreatedAt()),
                $this->translator->trans($tag->getStatus())
            ];
        }

        return [
            'columns' => ['ID', '名称', '别名', '创建时间', '状态'],
            'entityName' => $this->entityName,
            'list' => $result,
            'ids' => $ids
        ];
    }
}
