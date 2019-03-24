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
use Yeskn\MainBundle\Entity\User;
use Yeskn\MainBundle\Twig\GlobalValue;
use Symfony\Bridge\Twig\Extension\AssetExtension;

class StartRenderUserListEvent extends AbstractCrudListEvent
{
    public $entityName = '用户';

    /**
     * @var User[]
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
                $this->linkColumn($tag->getUsername(), 'member_home', ['username' => $tag->getUsername()]),
                $this->imgColumn($tag->getAvatar(), 50),
                $tag->getNickname(),
                $tag->getEmail(),
                $this->globalValue->ago($tag->getRegisterAt()),
                $tag->getGold()
            ];
        }

        return [
            'columns' => ['ID', '用户名', '头像', '昵称', '邮箱', '注册时间', '金币'],
            'column_width' => [0 => 5, 2 => 10, 4 => 15],
            'entityName' => $this->entityName,
            'list' => $result,
            'ids' => $ids
        ];
    }
}
