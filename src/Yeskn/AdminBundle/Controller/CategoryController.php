<?php

/**
 * This file is part of project yeskn-studio/vmoex-framework.
 *
 * Author: Jake
 * Create: 2018-09-15 09:38:44
 */

namespace Yeskn\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Yeskn\MainBundle\Entity\Category;
use Yeskn\MainBundle\Form\CategoryType;

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
            'list' => $list,
            'form' => $this->createForm(CategoryType::class, new Category())->createView()
        ]);
    }

    /**
     * @Route("/add", name="admin_category_add")
     *
     * @param $request
     * @return JsonResponse
     */
    public function addAction(Request $request)
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->get('doctrine.orm.entity_manager');

            $em->persist($category);

            $em->flush();

            $this->addFlash('success', '操作成功');

            return new JsonResponse(['status' => 1, 'message' => '操作成功']);
        }

        return new JsonResponse(['ret' => 0, 'msg' => $form->getErrors()->current()->getMessage()]);
    }
}
