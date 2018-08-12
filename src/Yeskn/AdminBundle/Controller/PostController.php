<?php

namespace Yeskn\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Yeskn\BlogBundle\Entity\Post;
use Yeskn\BlogBundle\Entity\Tag;

/**
 * Class PostController
 * @Route("/admin/post")
 * @Security("has_role('ROLE_ADMIN')")
 */
class PostController extends Controller
{
    /**
     * @Route("/create")
     * @param $request Request
     * @Method({"GET","POST"})
     * @return Response
     */
    public function createAction(Request $request)
    {
        $post = new Post();

        $form = $this->createForm('Yeskn\BlogBundle\Form\PostType', $post)
            ->add('saveCraft', 'Symfony\Component\Form\Extension\Core\Type\SubmitType');

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $post->setCreatedAt(new \DateTime());
            $post->setUpdatedAt(new \DateTime());
            $post->setIsDeleted(false);
            $post->setStatus('published');
            $post->setExcerpt('');

            $entityManager->persist($post);
            $entityManager->flush();

            $this->addFlash('success', '创建文章成功');
            return $this->redirectToRoute('yeskn_admin_post_list');
        }

        return $this->render('@YesknAdmin/Post/create.html.twig', array(
            'post' => $post,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/edit")
     */
    public function editAction(Request $request)
    {
        $post = $this->getDoctrine()->getRepository('YesknBlogBundle:Post')
            ->find($request->get('id'));

        $form = $this->createForm('Yeskn\BlogBundle\Form\PostType', $post)
            ->add('saveCraft', 'Symfony\Component\Form\Extension\Core\Type\SubmitType');

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $post->setUpdatedAt(new \DateTime());

            $entityManager->flush();

            $this->addFlash('success', '编辑文章成功');
            return $this->redirectToRoute('yeskn_admin_post_list');
        }

        return $this->render('@YesknAdmin/Post/create.html.twig', array(
            'post' => $post,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/delete/{id}")
     * @inheritdoc
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $post = $this->getDoctrine()->getRepository('YesknBlogBundle:Post')->find($id);
        if ($post) {
            $em->remove($post);
            $em->flush();
            $this->addFlash('success','delete success');
            return $this->redirectToRoute('yeskn_admin_post_list');
        } else {
            $this->addFlash('error','no post found for id' . $id);
            return $this->redirectToRoute('yeskn_admin_post_list');
        }
    }

    /**
     * @Route("/preview")
     */
    public function previewAction()
    {

    }

    /**
     * @Route("/list")
     * @Route("/index")
     * @Route("/")
     */
    public function listAction()
    {
        /**
         * @var Post[] $posts
         */
        $posts = $this->getDoctrine()->getRepository('YesknBlogBundle:Post')->findBy([], ['updatedAt' => 'DESC']);

        return $this->render('@YesknAdmin/Post/index.html.twig', array(
            'posts' => $posts
        ));
    }
}