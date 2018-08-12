<?php

/*
 * This file is part of project yeskn/vmoex.
 *
 * (c) Jaggle <jaggle@yeskn.com>
 *
 * created at 2018-05-29 16:30:40
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Yeskn\WebBundle\Utils;

use PHPHtmlParser\Dom;
use Psr\Container\ContainerInterface;

class HtmlPurer
{
    private $result = '';

    private $hasColor = false;

    private $container;

    /**
     * @var Dom $handler
     */
    private static $handler = null;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param $html
     * @return HtmlPurer
     */
    public function pure($html)
    {
        if (empty(self::$handler)) {
            self::$handler = new Dom();
        }

        $dom = self::$handler->load($html);

        $this->clearDOM($dom);

        $this->result = $dom->root->outerHtml();
        return $this;
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
                    $this->hasColor = true;
                    $node->setAttribute('style', 'background-color:'.$bgColor);
                }

                if (!empty($fontColor)) {
                    $this->hasColor = true;
                    $node->setAttribute('color', $fontColor);
                }
            }

            if (!empty($attrs['color'])) {
                $node->setAttribute('color', $attrs['color']);
            }

            if (!empty($attrs['data-pjax'])) {
                $node->setAttribute('data-pjax', $attrs['data-pjax']);
            }

            $nodeName = strtolower($node->getTag()->name());
            if ($nodeName === 'a' and !empty($attrs['href'])) {
                $node->setAttribute('href', $attrs['href']);
            }

            if ($nodeName == 'img' and !empty($attrs['src'])) {
                $node->setAttribute('src', $attrs['src']);
            }

            if ($nodeName === 'span' and !empty($attrs['data-at'])) {
                $aNode = new Dom\HtmlNode('a');
                $aNode->setAttribute('href', $this->container->get('router')
                    ->generate('user_home', ['username' => $attrs['data-at']]));
                $aNode->setAttribute('data-pjax', 1);

                $aNode->addChild(new Dom\TextNode($node->text() . ' '));

                foreach ($node->getChildren() as  $child) {
                    $node->removeChild($child->id());
                }

                $node->addChild($aNode);
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

    public function getResult()
    {
        $this->result = trim($this->result);
        $this->result = preg_replace('#<p *><br */*></p>$#', '', $this->result);
        $this->result = preg_replace('#<p> *</p>$#', '', $this->result);
        return $this->result;
    }

    public function hasColor() {
        return $this->hasColor;
    }

}
