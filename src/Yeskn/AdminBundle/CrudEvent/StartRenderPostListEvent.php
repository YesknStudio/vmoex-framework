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
    public $entityName = '文章';

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
                sprintf('<a href="%s">%s</a>', $this->router->generate('post_show', [
                    'id' => $post->getId()
                ]), $post->getTitle()),
                sprintf('<a href="%s">%s</a>', $this->router->generate('member_home', [
                    'username' => $post->getAuthor()->getUsername()
                ]), $post->getAuthor()->getNickname()),
                $this->globalValue->ago($post->getCreatedAt()),
                $this->globalValue->ago($post->getUpdatedAt()),
                $post->getViews(),
                $this->translator->trans($post->getStatus())
            ];
        }

        return [
            'columns' => ['ID', '标题', '作者', '发布日期', '更新日期', '点击', '状态'],
            'column_width' => [0 => '5', 1 => 25, 6 => 8, 7 => 20],
            'entityName' => $this->entityName,
            'list' => $result,
            'ids' => $ids,
            'create_btn' => 'yeskn_admin_post_create'
        ];
    }
}
