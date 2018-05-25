<?php

namespace Yeskn\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Yeskn\BlogBundle\Entity\Post;
use Yeskn\BlogBundle\Entity\Tag;

/**
 * Class PostController
 * @Route("/admin/post")
 * @Security("has_role('ROLE_ADMIN')")
 */
class PostController extends AdminCommonController
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
            $post->setIsDeleted(false);
            $post->setAuthor($this->getUser());
            $post->setStatus('published');

            $post->addCategory($this->getDoctrine()->getRepository('YesknBlogBundle:Category')
                ->find($request->get('select-category'))
            );

            $requestTags = trim($request->get('input-tag'));
            if ($requestTags) {
                $_tags = explode(',', $requestTags);
                foreach ($_tags as $_tag) {
                    if ($currentTag = $this->getDoctrine()->getRepository('YesknBlogBundle:Tag')
                        ->findOneBy(array('name' => $_tag))
                    ) {
                        $post->addTag($currentTag);
                    } else {
                        $currentTag = new Tag();
                        $currentTag->setName($_tag);
                        $currentTag->setStatus(1);
                        $currentTag->setSlug(uniqid());
                        $currentTag->setCreatedAt(new \DateTime());
                        $post->addTag($currentTag);
                        $entityManager->persist($currentTag);
                    }
                }
            }
            $entityManager->persist($post);
            $entityManager->flush();

            $this->addFlash('success', 'post.created_successfully');
            return $this->redirectToRoute('yeskn_admin_post_list');
        }

        $categories = $this->getDoctrine()->getRepository('YesknBlogBundle:Category')->findAll();
        $tags = $this->getDoctrine()->getRepository('YesknBlogBundle:Tag')->findBy([], [
            'id' => 'DESC'
        ], 5);

        return $this->render('@YesknAdmin/Post/create.html.twig', array(
            'post' => $post,
            'form' => $form->createView(),
            'categories' => $categories,
            'tags' => $tags
        ));
    }

    /**
     * @Route("/edit")
     */
    public function editAction()
    {

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
        $posts = $this->getDoctrine()->getRepository('YesknBlogBundle:Post')->findAll();

        return $this->render('@YesknAdmin/Post/index.html.twig', array(
            'posts' => $posts
        ));
    }
}