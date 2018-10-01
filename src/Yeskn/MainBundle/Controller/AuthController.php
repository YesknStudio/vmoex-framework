<?php

/**
 * This file is part of project yeskn-studio/wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-14 15:26:13
 */

namespace Yeskn\MainBundle\Controller;

use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Yeskn\MainBundle\Entity\OpenUser;
use Yeskn\MainBundle\Entity\User;
use Yeskn\MainBundle\Form\UserLoginType;
use Yeskn\Support\Http\HttpResponse;

class AuthController extends Controller
{
    use HttpResponse;

    /**
     * @Route("/login", name="login", methods={"GET"})
     */
    public function loginAction()
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        //get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        //last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('@YesknMain/auth/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error
        ));
    }

    /**
     * @Route("/register", name="register")
     * @param $request Request
     * @throws
     * @return RedirectResponse|Response
     */
    public function regAction(Request $request)
    {
        // 1) build the form
        $user = new User();
        $form = $this->createForm(UserLoginType::class, $user);
        $em = $this->getDoctrine()->getManager();

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $check = $em->getRepository('YesknMainBundle:User')
                ->checkEmailAndUsername($user->getEmail(), $user->getUsername());

            if ($check) {
                $this->addFlash('danger', '用户名或者邮箱已经注册');
                return $this->render('@YesknMain/auth/register.html.twig',[
                    'form' => $form->createView()
                ]);
            }

            // 3) Encode the password (you could also do this via Doctrine listener)
            $user->setSalt(md5(uniqid()));
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            $user->setRegisterAt(new \DateTime());

            $fileName = md5($user->getUsername()) . '.png';
            $file = $this->container->getParameter('kernel.project_dir') . '/web/avatar/' . $fileName;

            $identicon = new \Identicon\Identicon();
            $avatar = $identicon->getImageData($user->getUsername(), 100);

            file_put_contents($file, $avatar);

            $user->setAvatar('avatar/' . $fileName);
            $user->setNickname($user->getUsername());
            $user->setLoginAt(new \DateTime());
            $user->setRole('ROLE_USER');

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', '注册成功，请使用账号名"' . $user->getUsername().'"登录');

            return $this->redirectToRoute('login');
        }

        return $this->render(
            '@YesknMain/auth/register.html.twig',
            array('form' => $form->createView())
        );
    }

    /**
     * @Route("/login/github", name="login_from_github")
     */
    public function loginFromGithubAction()
    {
        return $this->redirect('https://github.com/login/oauth/authorize?' . http_build_query([
            'client_id' => $this->getParameter('github_client_id'),
            'redirect_uri' => 'https://www.wpcraft.cn/oauth/github',
            'scopes' => 'user',
            'state' => 'github'
        ]));
    }

    /**
     * @Route("/oauth/github", name="oauth_github")
     */
    public function oauthGithub(Request $request)
    {
        $code = $request->get('code');

        $http = new Client();

        $response = $http->post('https://github.com/login/oauth/access_token', [
            'form_params' => [
                'client_id' => $this->getParameter('github_client_id'),
                'client_secret' => $this->getParameter('github_client_secret'),
                'code' => $code,
                'redirect_uri' => 'https://www.wpcraft.cn/oauth/github',
                'state' => 'github'
            ],
            'headers' => [
                'Accept' => 'application/json'
            ]
        ]);

        $response = json_decode($response->getBody()->getContents());

        $access_token = $response->access_token;

        $response = $http->get('https://api.github.com/user', [
            'headers' => [
                'Authorization' => 'token ' . $access_token
            ]
        ]);

        $response = json_decode($response->getBody()->getContents());

        // login success
        if (!empty($response->node_id)) {
            $openUser = $this->getDoctrine()->getRepository('YesknMainBundle:OpenUser')->findOneBy([
                'githubNodeId' => $response->node_id
            ]);

            if (!$openUser) {
                $user = new User();
                $user->setUsername($response->login);
                $user->setNickname($response->name);
                $user->setEmail($response->email);
                $user->setAvatar($response->avatar_url);
                $user->setRole('ROLE_USER');
                $user->setLoginAt(new \DateTime());
                $user->setRegisterAt(new \DateTime());
                $user->setRemark($response->bio);

                $openUser = new OpenUser();

                $openUser->setUser($user);
                $openUser->setGithubNodeId($response->node_id);

                $this->get('doctrine.orm.entity_manager')->persist($user);
                $this->get('doctrine.orm.entity_manager')->persist($openUser);

                $this->get('doctrine.orm.entity_manager')->flush();
            } else {
                $user = $openUser->getUser();
            }

            $token = new UsernamePasswordToken($user, $user->getPassword(), "public", $user->getRoles());
            $this->get("security.token_storage")->setToken($token);

            $event = new InteractiveLoginEvent($request, $token);
            $this->get("event_dispatcher")->dispatch("security.interactive_login", $event);

            $this->redirectToRoute('homepage');
        }

        return $this->errorResponse('github授权失败');
    }

    /**
     * @Route("/login", name="do_login", methods={"POST"})
     */
    public function doLoginAction()
    {
        throw new \Exception('this should never be reached!');
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction()
    {
        throw new \Exception('this should never be reached!');
    }
}
