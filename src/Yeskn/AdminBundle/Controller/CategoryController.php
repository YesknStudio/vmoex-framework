<?php

/**
 * This file is part of project wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-15 09:38:44
 */

namespace Yeskn\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Yeskn\MainBundle\Entity\Category;

/**
 * Class CategoryController
 * @package Yeskn\AdminBundle\Controller
 *
 * @Route("/category")
 */
class CategoryController extends Controller
{
    /**
     * @Route("/", name="admin_category_index")
     */
    public function indexAction()
    {
        /** @var Category[] $list */
        $list = $this->getDoctrine()->getRepository('YesknMainBundle:Category')->findAll();

        return $this->render('@YesknAdmin/category/index.html.twig', [
            'list' => $list
        ]);
    }
}