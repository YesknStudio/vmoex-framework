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
use Yeskn\MainBundle\Entity\Translation;
use Yeskn\MainBundle\Twig\GlobalValue;

class StartRenderTranslationListEvent extends AbstractCrudListEvent
{
    /**
     * @var Translation[]
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
                $tag->getMessageId(),
                $tag->getChinese(),
                $tag->getEnglish(),
                $tag->getTaiwanese(),
                $tag->getJapanese(),
            ];
        }

        return [
            'columns' => ['ID', 'messageId', '中文简体', '英文', '中文繁体', '日语'],
            'entitySubTitle' => '如果你需要修改网站的词条的话。',
            'column_width' => [0 => '5'],
            'list' => $result,
            'ids' => $ids
        ];
    }
}
