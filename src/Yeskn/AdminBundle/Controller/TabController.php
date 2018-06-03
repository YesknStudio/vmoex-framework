<?php
/**
 * This file is part of project Vmoex.
 *
 * Author: Jake
 * Create: 2018-06-03 21:14:28
 */

namespace Yeskn\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Yeskn\BlogBundle\Entity\Tab;

/**
 * Class TabController
 * @package Yeskn\AdminBundle\Controller
 *
 * @Route("/admin/tab")
 */
class TabController extends Controller
{
    /**
     * @Route("/index", name="admin_tab_index")
     */
    public function indexAction()
    {
        $tabs = $this->container->get('doctrine')->getRepository('YesknBlogBundle:Tab')
            ->findAll();
        return $this->render('@YesknAdmin/Tab/index.html.twig',array(
            'tabs' => $tabs
        ));
    }

    /**
     * @inheritdoc
     * @Route("/add")
     */
    public function addAction(Request $request)
    {
        $tab = new Tab();
        $form = $this->createFormBuilder($tab)
            ->add('name', TextType::class,array('label' => '板块名'))
            ->add('alias',TextType::class,array('label' => '别名'))
            ->add('submit',SubmitType::class,array('label' => '提交'))
            ->getForm();

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($tab);
            $em->flush();
            $this->addFlash('success','创建板块成功');
            return $this->redirectToRoute('admin_tab_index');
        }
        return $this->render('@YesknAdmin/Tab/add.html.twig',array(
            'form' => $form->createView()
        ));
    }

    /**
     * @param Request $request
     * @Route("/edit")
     * @return Response
     */
    public function editAction(Request $request)
    {
        $tabAlias = $request->get('tab');
        $tab = $this->getDoctrine()->getRepository('YesknBlogBundle:Tab')
            ->findOneBy(['alias' => $tabAlias]);

        $form = $this->createFormBuilder($tab)
            ->add('name', TextType::class,array('label' => '板块名'))
            ->add('alias',TextType::class,array('label' => '别名'))
            ->add('submit',SubmitType::class,array('label' => '提交'))
            ->getForm();

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($tab);
            $em->flush();
            $this->addFlash('success','修改板块成功');
            return $this->redirectToRoute('admin_tab_index');
        }
        return $this->render('@YesknAdmin/Tab/add.html.twig',array(
            'form' => $form->createView()
        ));
    }

    /**
     * @param Request $request
     * @Route("/delete")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request)
    {
        $tabAlias = $request->get('tab');
        $tab = $this->getDoctrine()->getRepository('YesknBlogBundle:Tab')
            ->findOneBy(['alias' => $tabAlias]);

        $em = $this->getDoctrine()->getManager();

        $em->remove($tab);

        $em->flush();

        $this->addFlash('success','删除板块成功');
        return $this->redirectToRoute('admin_tab_index');
    }
}