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
use Yeskn\MainBundle\Entity\Post;
use Yeskn\MainBundle\Twig\GlobalValue;

class StartRenderPostListEvent extends AbstractCrudListEvent
{
    /**
     * @var Post[]
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

        foreach ($this->list as $post) {
            $ids[] = $post->getId();

            $result[] = [
                $post->getId(),
                $this->linkColumn($post->getTitle(), 'post_show', ['id' => $post->getId()]),
                $this->linkColumn($post->getAuthor()->getNickname(), 'member_home', [
                    'username' => $post->getAuthor()->getUsername()
                ]),
                $this->linkColumn($post->getTab()->getName(), 'post_list', ['tab' => $post->getTab()->getAlias()]),
                $this->globalValue->ago($post->getCreatedAt()),
                $this->globalValue->ago($post->getUpdatedAt()),
                $post->getViews(),
                $this->translator->trans($this->statusLabel($post->getStatus()))
            ];
        }

        return [
            'columns' => ['ID', '标题', '作者', '板块', '发布日期', '更新日期', '点击', '状态'],
            'column_width' => [0 => '5', 1 => 25, 6 => 8, 7 => 20],
            'list' => $result,
            'ids' => $ids,
            'create_btn' => 'yeskn_admin_post_create',
            'edit_btn' => 'yeskn_admin_post_edit'
        ];
    }

    public function statusLabel($status)
    {
        $mappings = [
            'published' => '已发布'
        ];

        return $mappings[$status];
    }
}
