<?php
/**
 * This file is part of project Vmoex.
 *
 * Author: Jake
 * Create: 2018-05-29 16:30:40
 */

namespace Yeskn\BlogBundle\Utils;


use PHPHtmlParser\Dom;

class HtmlPurer
{
    /**
     * @var Dom $handler
     */
    private static $handler = null;

    /**
     * @param $html
     * @return string
     */
    public function pure($html)
    {
        if (empty(self::$handler)) {
            self::$handler = new Dom();
        }

        $dom = self::$handler->load($html);

        $this->clearDOM($dom);

        return $dom->root->outerHtml();
    }

    /**
     * @param $node
     */
    public function clearNode(&$node)
    {
        if ($node instanceof Dom\HtmlNode) {
            $attrs = $node->getAttributes();

            $node->removeAllAttributes();

            if (!empty($attrs['style'])) {
                $style = $attrs['style'];

                // style属性只允许background-color exp: style="back-ground-color:#fff;position:absolute;"
                $styles = explode(';', $style);

                foreach ($styles as $item) {
                    $keyInfo = explode(':', $item);
                    if (trim($keyInfo[0]) === 'background-color'
                        && strpos($keyInfo[1], 'rgb') !== false)
                    {
                        $bgColor = $keyInfo[1];
                    }

                    if (trim($keyInfo[0]) === 'color') {
                        $fontColor = $keyInfo[1];
                    }
                }

                if (!empty($bgColor)) {
                    $node->setAttribute('style', 'background-color:'.$bgColor);
                }

                if (!empty($fontColor)) {
                    $node->setAttribute('color', $fontColor);
                }
            }

            if (!empty($attrs['color'])) {
                $node->setAttribute('color', $attrs['color']);
            }

            if (strtolower($node->getTag()->name()) === 'a' and !empty($attrs['href'])) {
                $node->setAttribute('href', $attrs['href']);
            }

            if (strtolower($node->getTag()->name()) == 'img' and !empty($attrs['src'])) {
                $node->setAttribute('src', $attrs['src']);
            }

            if ($node->hasChildren()) {
                $children = $node->getChildren();

                foreach ($children as $k => $sub) {
                    $this->clearNode($sub);
                }
            }
        }
    }

    /**
     * @param $dom
     */
    function clearDOM(&$dom)
    {
        /**
         * @var Dom\InnerNode|Dom\TextNode|Dom\HtmlNode $item
         */
        foreach ($dom->root->getIterator() as $key => &$item) {
            $this->clearNode($item);
        }
    }

}
