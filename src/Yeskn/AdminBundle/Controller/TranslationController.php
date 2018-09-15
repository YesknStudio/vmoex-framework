<?php

/**
 * This file is part of project yeskn-studio/wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-14 21:35:55
 */

namespace Yeskn\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Yeskn\MainBundle\Entity\Translation;
use Yeskn\MainBundle\Form\TranslateType;

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

        $form = $this->createForm(TranslateType::class, $translate);

        return $this->render('@YesknAdmin/translation/index.html.twig', [
            'list' => $list,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/add", name="admin_translation_add", methods={"POST"})
     *
     * @param $request
     * @return JsonResponse
     */
    public function addAction(Request $request)
    {
        $translate = new Translation();

        $form = $this->createForm(TranslateType::class, $translate);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($translate);
            $entityManager->flush();

            $this->addFlash('success', '添加成功');

            return new JsonResponse(['ret' => 1]);
        }

        return new JsonResponse(['ret' => 0, 'msg' => $form->getErrors()->current()->getMessage()]);
    }
}