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

namespace Yeskn\MainBundle\Utils;

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
    private $handler = null;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        $this->handler = new Dom();
    }

    /**
     * @param $node
     * @param $supportStyle
     */
    public function clearNode(&$node, $supportStyle = true)
    {
        if ($node instanceof Dom\HtmlNode) {
            $tagName = strtolower($node->getTag()->name());
            if ($tagName === 'script') {
                $node->delete();
                return ;
            }
            $attrs = $node->getAttributes();

            $node->removeAllAttributes();

            if (!empty($attrs['style'])) {
                $style = $attrs['style'];

                // style属性只允许background-color exp: style="back-ground-color:#fff;position:absolute;"
                $styles = explode(';', trim($style, '; '));
                $styleStr = ''; // 重新组装后的style

                foreach ($styles as $item) {
                    list($styleName, $styleValue) = explode(':', $item);

                    $styleName = trim($styleName); $styleValue = trim($styleValue);

                    if ($supportStyle) { // 当支持任意属性时，去除 display,position,onerror,onload属性，防止污染页面布局
                        if (in_array($styleName, ['display', 'position', 'onerror', 'onload'])) {
                            continue;
                        } else {
                            $styleStr .= $styleName . ':' . $styleValue . ';';
                        }
                    } else {
                        // 当不支持任意属性时，还是允许修改文本的背景色和文字颜色，但是不支持设置背景图片
                        if ($styleName === 'background-color' && strpos($styleValue, 'rgb') !== false)
                        {
                            $styleStr .= $styleName . ':' . $styleValue . ';';
                        }

                        if ($styleName === 'color') {
                            $styleStr .= $styleName . ':' . $styleValue . ';';
                        }
                    }
                }

                $node->setAttribute('style', $styleStr);
            }

            if (!empty($attrs['class']) && $attrs['class'] === 'atwho-query') {

            }

            $allowedAttr = ['color', 'data-pjax', 'href', 'src'];

            foreach ($allowedAttr as $item) {
                if (!empty($attrs[$item])) {
                    $node->setAttribute($item, $attrs[$item]);
                }
            }

            // at功能已经自己实现了
//            if ($nodeName === 'span' and !empty($attrs['data-at'])) {
//                $aNode = new Dom\HtmlNode('a');
//                $aNode->setAttribute('href', $this->container->get('router')
//                    ->generate('user_home', ['username' => $attrs['data-at']]));
//                $aNode->setAttribute('data-pjax', 1);
//
//                $aNode->addChild(new Dom\TextNode($node->text() . ' '));
//
//                foreach ($node->getChildren() as  $child) {
//                    $node->removeChild($child->id());
//                }
//
//                $node->addChild($aNode);
//            }

            if ($node->hasChildren()) {
                $children = $node->getChildren();

                foreach ($children as $k => $sub) {
                    $this->clearNode($sub, $supportStyle);
                }
            }
        }
    }

    /**
     * @param $dom
     * @param $supportStyle
     */
    function clearDOM(&$dom, $supportStyle = true)
    {
        /**
         * @var Dom\InnerNode|Dom\TextNode|Dom\HtmlNode $item
         */
        foreach ($dom->root->getIterator() as $key => &$item) {
            $this->clearNode($item, $supportStyle);
        }
    }

    public function getResult($allowLine = false)
    {
        if ($allowLine === false){
            $this->result = trim($this->result);
            $this->result = preg_replace('#<p *><br */*></p>$#', '', $this->result);
            $this->result = preg_replace('#<p> *</p>$#', '', $this->result);
        }

        return $this->result;
    }

    public function hasColor() {
        return $this->hasColor;
    }


    /**
     * 优化行内文本，防止出现不必要的样式，和js代码
     *
     * @param $html
     * @return  $this
     */
    public function pureInlineText($html)
    {
        $dom = $this->handler->load($html);

        $this->clearDOM($dom, false);

        $this->result = $dom->root->outerHtml();
        return $this;
    }

    /**
     * 优化富文本内容，防止出现不必要的样式，和JS代码
     *
     * @param $html
     * @return $this
     */
    public function pureHtmlText($html)
    {
        $dom = $this->handler->load($html);

        $this->clearDOM($dom, true);

        $this->result = $dom->root->outerHtml();
        return $this;
    }

}
