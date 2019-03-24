<?php

/**
 * This file is part of project yeskn-studio/vmoex-framework.
 *
 * Author: Jake
 * Create: 2018-09-18 20:33:48
 */

namespace Yeskn\AdminBundle\CrudEvent;

use Symfony\Bridge\Twig\Extension\AssetExtension;
use Symfony\Component\Routing\RouterInterface;

abstract class AbstractCrudListEvent implements CrudListEventInterface
{
    /**
     * @var AssetExtension
     */
    protected $asset;

    protected $list;

    /**
     * @var RouterInterface
     */
    protected $router;

    public function setList(array $list)
    {
        $this->list = $list;

        return $this;
    }

    protected function getList()
    {
        return $this->list;
    }

    public function imgColumn($val, $width = 100)
    {
        if (empty($val)) return '';

        $val = $this->asset->getAssetUrl($val);
        return sprintf('<img width="%spx" height="%spx" src="%s" />', $width, $width, $val);
    }

    public function linkColumn($text, $route, $params = null)
    {
        if (is_null($params)) {
            return sprintf("<a href='%s'>%s</a>", $route, $text);
        }

        return sprintf("<a href='%s'>%s</a>", $this->router->generate($route, $params), $text);
    }

    abstract function execute();
}
