<?php

/**
 * This file is part of project yeskn-studio/wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-14 21:35:55
 */

namespace Yeskn\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Yeskn\MainBundle\Entity\Translation;
use Yeskn\MainBundle\Form\TranslationType;

/**
 * Class TransactionController
 * @package Yeskn\AdminBundle\Controller
 *
 * @Route("/translation")
 */
class TranslationController extends Controller
{
    /**
     * @Route("/", name="admin_translation_index")
     */
    public function indexAction()
    {
        /** @var Translation[] $list */
        $list = $this->getDoctrine()->getRepository('YesknMainBundle:Translation')->findAll();

        $translate = new Translation();

        $form = $this->createForm(TranslationType::class, $translate);

        return $this->render('@YesknAdmin/translation/index.html.twig', [
            'list' => $list,
            'form' => $form->createView()
        ]);
    }
}