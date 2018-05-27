<?php
/**
 * This file is part of project JetBlog.
 *
 * Author: Jake
 * Create: 2018-05-27 16:54:40
 */

namespace Yeskn\BlogBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class GalGameController extends Controller
{
    /**
     * @Route("/galgame", name="gal_game")
     */
    public function galGameAction()
    {
        return $this->render('@YesknBlog/galgame.html.twig');
    }
}