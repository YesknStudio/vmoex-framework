<?php
/**
 * Created by PhpStorm.
 * User: Jake
 * Date: 2016/6/23
 * Time: 12:07
 */

namespace Yeskn\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Yeskn\BlogBundle\Entity\Category;

/**
 * Class CategoryController
 * @Route("/admin/category")
 * @package Yeskn\AdminBundle\Controller
 */
class CategoryController extends Controller
{
    /**
     * @Route("/index")
     */
    public function indexAction()
    {
        $categories = $this->container->get('doctrine')->getRepository('YesknBlogBundle:Category')
            ->findAll();
        return $this->render('@YesknAdmin/Category/index.html.twig',array(
            'categories' => $categories
        ));
    }

    /**
     * @inheritdoc
     * @Route("/add")
     */
    public function addAction(Request $request)
    {
        $category = new Category();
        $form = $this->createFormBuilder($category)
            ->add('name',TextType::class,array('label' => '分类名'))
            ->add('slug',TextType::class,array('label' => '别名'))
            ->add('submit',SubmitType::class,array('label' => '提交'))
            ->getForm();

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $category->setStatus(1);
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
            $this->addFlash('success','创建分类成功');
            return $this->redirectToRoute('yeskn_admin_category_index');
        }
        return $this->render('@YesknAdmin/Category/add.html.twig',array(
            'form' => $form->createView()
        ));
    }
}