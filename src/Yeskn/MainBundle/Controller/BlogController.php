<?php

/**
 * This file is part of project wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-22 14:08:49
 */

namespace Yeskn\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Yeskn\MainBundle\Entity\Blog;
use Yeskn\Support\Http\ApiFail;
use Yeskn\Support\Http\ApiOk;
use Yeskn\Support\Http\HttpResponse;

class BlogController extends Controller
{
    use HttpResponse;

    /**
     * @return Response
     * @throws \LogicException
     *
     * @Route("/blog/create/step/{step}", name="blog_create", requirements={"step": "\d+"}, defaults={"step":1})
     */
    public function createAction(Request $request, $step)
    {
        $blogRepo = $this->getDoctrine()->getRepository('YesknMainBundle:Blog');
        $blog = $blogRepo->findOneBy(['user' => $this->getUser()], ['id' => 'DESC']);

        if ($blog && $blog->getStatus() == 'created') {
            return $this->errorResponse('你已经创建了一个博客，无法再创建更多了');
        } else {
            if (empty($blog)) {
                if ($step == 1) {
                    $blog = new Blog();
                    $blog->setUser($this->getUser());
                } else {
                    return $this->errorResponse('请回到第一步进行创建');
                }
            }
        }

        if ($request->isMethod('POST')) {

            if ($step == 1) {
                $blogName = $request->get('blogName');
                $blogDesc = $request->get('blogDesc');

                if (empty($blogName)) {
                    return new ApiFail('请输入博客名称！');
                }

                if (empty($blogDesc)) {
                    return new ApiFail('请输入博客描述！');
                }

                $blog->setTitle($blogName);
                $blog->setSubtitle($blogDesc);
                $blog->setCreatedAt(new \DateTime());
                $blog->setUpdatedAt(new \DateTime());

                $this->get('doctrine.orm.entity_manager')->persist($blog);
                $this->get('doctrine.orm.entity_manager')->flush();

                return new ApiOk();
            }

            if ($step == 2) {
                $subdomain = $request->get('subDomain');
                if (empty($subdomain)) {
                    return new ApiFail('请输入博客域名！');
                }

                $blog->setSubdomain($subdomain);
                $this->get('doctrine.orm.entity_manager')->flush();

                return new ApiOk();
            }

            if ($step == 3) {
                $password = $request->get('password');
                if (empty($password)) {
                    return new ApiFail('请输入博客密码！');
                }

                $blog->setPassword($password);
                $this->get('doctrine.orm.entity_manager')->flush();

                return new ApiOk();
            }
        }

        if ($step == 4 && !empty($blog->getPassword()) && $blog->getStatus() == Blog::STATUS_STARING) {
            $blog->setStatus(Blog::STATUS_QUEUEING);
            $this->get('doctrine.orm.entity_manager')->flush();
        }

        return $this->render('@YesknMain/blog/create.html.twig', [
            'step' => $step
        ]);
    }

    /**
     * @Route("/blog/{subdomain}", name="blog_info")
     */
    public function detailAction($subdomain)
    {
        $blogRepo = $this->getDoctrine()->getRepository('YesknMainBundle:Blog');
        $blog = $blogRepo->findOneBy(['subdomain' => $subdomain]);

        if (empty($blog)) {
            return $this->render('@YesknMain/error.html.twig', [
                'message' => 'err_no_blog_found'
            ]);
        }

        return $this->render('@YesknMain/blog/info.html.twig', [
            'blog' => $blog
        ]);
    }
}