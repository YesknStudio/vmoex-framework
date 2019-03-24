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
use Yeskn\MainBundle\Entity\Tab;
use Symfony\Bridge\Twig\Extension\AssetExtension;

use Yeskn\MainBundle\Twig\GlobalValue;

class StartRenderTabListEvent extends AbstractCrudListEvent
{
    public $entityName = '板块';

    /**
     * @var Tab[]
     */
    protected $list;

    private $router;

    private $globalValue;

    private $translator;

    public function __construct(RouterInterface $router, GlobalValue $globalValue, TranslatorInterface $translator
    , AssetExtension $asset) {
        $this->router = $router;
        $this->globalValue = $globalValue;
        $this->translator = $translator;
        $this->asset = $asset;
    }

    public function execute()
    {
        $ids = $result = [];

        foreach ($this->list as $tab) {
            $ids[] = $tab->getId();

            $result[] = [
                $tab->getId(),
                $tab->getName(),
                $this->imgColumn($tab->getAvatar()),
                $tab->getLevel(),
                $tab->getAlias(),
                $tab->getDescription()
            ];
        }

        return [
            'columns' => ['ID', '名称', '图标', '层级', '别名', '描述'],
            'entityName' => $this->entityName,
            'list' => $result,
            'ids' => $ids
        ];
    }
}
