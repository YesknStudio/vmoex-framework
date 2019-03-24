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
use Yeskn\MainBundle\Entity\Comment;
use Yeskn\MainBundle\Twig\GlobalValue;

class StartRenderCommentListEvent extends AbstractCrudListEvent
{
    /**
     * @var Comment[]
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
            $post = $tag->getPost();

            $result[] = [
                $tag->getId(),
                $tag->getUser()->getNickname(),
                $tag->getContent(),
                $this->linkColumn($post->getTitle(), 'post_show',  ['id' => $post->getId()]),
                $this->globalValue->ago($tag->getCreatedAt()),
            ];
        }

        return [
            'columns' => ['ID', '作者', '内容', '文章标题', '发布日期'],
            'column_width' => [0 => 5, 1 => 10, 4 => 10, 5 => 15],
            'list' => $result,
            'ids' => $ids
        ];
    }
}
