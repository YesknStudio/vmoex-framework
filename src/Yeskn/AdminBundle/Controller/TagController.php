<?php
/**
 * Created by PhpStorm.
 * User: Jake
 * Date: 2016/6/22
 * Time: 20:15
 */

namespace Yeskn\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Yeskn\BlogBundle\Entity\Tag;

/**
 * Class TagController
 * @Route("/admin/tag")
 * @package Yeskn\AdminBundle\Controller
 */
class TagController extends Controller
{
    /**
     * @Route("/create")
     */
    public function newAction(Request $request)
    {
        $tag = new Tag();
        $form = $this->createFormBuilder($tag)
            ->add('name' , TextType::class,array('label' => '标签名'))
            ->add('slug',TextType::class,array('label' => '别名'))
            ->add('submit',SubmitType::class , array('label' => '提交'))
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $tag->setStatus(1);
            $tag->setCreatedAt(new \DateTime());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($tag);
            $entityManager->flush();
            $this->addFlash('success', '创建标签成功');
            return $this->redirectToRoute('yeskn_admin_tag_new');
        }
        return  $this->render('@YesknAdmin/Tag/new.html.twig',array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/edit")
     */
    public function editAction(Request $request)
    {
        $tag = $this->getDoctrine()->getRepository('YesknBlogBundle:Tag')
            ->find($request->get('id'));

        $form = $this->createFormBuilder($tag)
            ->add('name' , TextType::class,array('label' => '标签名'))
            ->add('slug',TextType::class,array('label' => '别名'))
            ->add('submit',SubmitType::class , array('label' => '提交'))
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->flush();
            $this->addFlash('success', '修改标签成功');
            return $this->redirectToRoute('yeskn_admin_tag_new');
        }
        return  $this->render('@YesknAdmin/Tag/new.html.twig',array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/index")
     */
    public function indexAction()
    {
        $tags = $this->getDoctrine()->getRepository('YesknBlogBundle:Tag')->findAll();
        return $this->render('@YesknAdmin/Tag/index.html.twig',array(
            'tags' => $tags
        ));
    }

    /**
     * @Route("/delete/{id}")
     * @inheritdoc
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $tag = $this->getDoctrine()->getRepository('YesknBlogBundle:Tag')->find($id);
        if ($tag) {
            $em->remove($tag);
            $em->flush();
            $this->addFlash('success','delete success');
            return $this->redirectToRoute('yeskn_admin_tag_index');
        } else {
            $this->addFlash('error','no tag found for id' . $id);
            return $this->redirectToRoute('yeskn_admin_tag_index');
        }
    }
}