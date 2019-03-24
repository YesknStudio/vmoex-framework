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
use Yeskn\MainBundle\Entity\Category;
use Yeskn\MainBundle\Twig\GlobalValue;

class StartRenderCategoryListEvent extends AbstractCrudListEvent
{
    public $entityName = '分类';

    /**
     * @var Category[]
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

        foreach ($this->list as $category) {
            $ids[] = $category->getId();

            $result[] = [
                $category->getId(),
                $category->getName(),
                $category->getSlug(),
                $this->translator->trans($category->getStatus() ? '启用' : '不启用')
            ];
        }

        return [
            'columns' => ['ID', '名称', '别名', '状态'],
            'entityName' => $this->entityName,
            'list' => $result,
            'ids' => $ids
        ];
    }
}
