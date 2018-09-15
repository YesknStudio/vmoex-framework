<?php

/**
 * This file is part of project wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-15 09:38:58
 */

namespace Yeskn\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Yeskn\MainBundle\Entity\Tab;
use Yeskn\MainBundle\Form\TabType;

/**
 * Class TabController
 * @package Yeskn\AdminBundle\Controller
 *
 * @Route("/tab")
 */
class TabController extends Controller
{
    /**
     * @Route("/", name="admin_tab_index")
     */
    public function indexAction()
    {
        /** @var Tab[] $list */
        $list = $this->getDoctrine()->getRepository('YesknMainBundle:Tab')->findAll();


        $form = $this->createForm(TabType::class, new Tab());

        return $this->render('@YesknAdmin/tab/index.html.twig', [
            'list' => $list,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/add", name="admin_tab_add", methods={"POST"})
     *
     * @param $request
     *
     * @return JsonResponse
     */
    public function addAction(Request $request)
    {
        $tab = new Tab();

        $form = $this->createForm(TabType::class, $tab);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->get('doctrine.orm.entity_manager');

            $em->persist($tab);
            $em->flush();

            $this->addFlash('success', '操作成功');

            return new JsonResponse(['status' => 1, 'message' => '操作成功']);
        }

        return new JsonResponse(['ret' => 0, 'msg' => $form->getErrors()->current()->getMessage()]);
    }
}