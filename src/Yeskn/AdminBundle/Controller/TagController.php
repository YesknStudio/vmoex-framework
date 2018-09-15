<?php

/**
 * This file is part of project wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-15 09:38:25
 */

namespace Yeskn\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Yeskn\MainBundle\Entity\Tag;
use Yeskn\MainBundle\Form\TagType;

/**
 * Class TagController
 * @package Yeskn\AdminBundle\Controller
 *
 * @Route("/tag")
 */
class TagController extends Controller
{
    /**
     * @Route("/", name="admin_tag_index")
     */
    public function indexAction()
    {
        /** @var Tag[] $list */
        $list = $this->getDoctrine()->getRepository('YesknMainBundle:Tag')->findAll();

        $form = $this->createForm(TagType::class, new Tag());

        return $this->render('@YesknAdmin/tag/index.html.twig', [
            'list' => $list,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/add", name="admin_tag_add")
     *
     * @param $request
     *
     * @return JsonResponse
     */
    public function addAction(Request $request)
    {
        $tag = new Tag();

        $form = $this->createForm(TagType::class, $tag);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tag->setCreatedAt(new \DateTime());

            $em = $this->get('doctrine.orm.entity_manager');

            $em->persist($tag);
            $em->flush();

            $this->addFlash('success', '操作成功');

            return new JsonResponse(['status' => 1, 'message' => '操作成功']);
        }

        return new JsonResponse(['ret' => 0, 'msg' => $form->getErrors()->current()->getMessage()]);
    }
}