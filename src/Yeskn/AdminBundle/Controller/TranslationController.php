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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Yeskn\AdminBundle\Services\LoadTranslationService;
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

            $this->get(LoadTranslationService::class)->execute();

            return new JsonResponse(['ret' => 1]);
        }

        return new JsonResponse(['ret' => 0, 'msg' => $form->getErrors()->current()->getMessage()]);
    }

    /**
     * @Route("/edit_{id}", name="admin_translation_edit")
     *
     * @param $id
     * @param $request
     *
     * @return Response
     */
    public function editAction(Request $request, $id)
    {
        $repo = $this->getDoctrine()->getRepository('YesknMainBundle:Translation');

        $trans = $repo->find($id);

        $form = $this->createForm(TranslateType::class, $trans);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->get('doctrine.orm.entity_manager');

            $em->persist($trans);
            $em->flush();


            $this->addFlash('success', '操作成功');

            return new JsonResponse(['status' => 1, 'message' => '操作成功']);
        }

        return $this->render('@YesknAdmin/modals/entity-modal.html.twig', [
            'form' => $form->createView(),
            'title' => '编辑翻译',
            'action' => $this->generateUrl('admin_translation_edit', ['id' => $id]),
            'formId' => $request->get('r')
        ]);
    }
}