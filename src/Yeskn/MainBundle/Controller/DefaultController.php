<?php

/**
 * This file is part of project yeskn-studio/wpcraft.
 *
 * Author: Jaggle
 * Create: 2018-09-12 14:30:12
 */

namespace Yeskn\MainBundle\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="wpcraft_index")
     */
    public function indexAction()
    {
        return $this->render('default/index.html.twig', [
            'base_dir' => $this->getParameter('kernel.project_dir')
        ]);
    }
}
