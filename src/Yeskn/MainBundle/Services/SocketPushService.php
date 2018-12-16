<?php

/**
 * This file is part of project project yeskn-studio/vmoex-framework.
 *
 * Author: Jaggle
 * Create: 2018-06-10 22:57:43
 */

namespace Yeskn\MainBundle\Services;

use GuzzleHttp\Client;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Yeskn\MainBundle\Entity\Message;
use Yeskn\MainBundle\Entity\User;
use Yeskn\MainBundle\Twig\GlobalValue;

class SocketPushService
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var
     */
    private $container;

    /**
     * @var GlobalValue
     */
    private $globalValue;

    private $socketPushHost;

    public function __construct(ContainerInterface $container, GlobalValue $globalValue)
    {
        $this->client = new Client();
        $this->container = $container;
        $this->globalValue = $globalValue;

        $this->socketPushHost = $container->getParameter('socket_push_host');
    }

    public function push($username, $event, $data)
    {
        $this->client->post($this->socketPushHost , [
            'form_params' => [
                'type' => 'publish',
                'to' => $username,
                'event' => $event,
                'data' => $data
            ]
        ]);
    }

    public function pushAll($event, $data = [])
    {
        $this->client->post($this->socketPushHost , [
            'form_params' => [
                'type' => 'publish',
                'event' => $event,
                'data' => $data
            ]
        ]);
    }

    public function pushNewMessage(Message $message)
    {
        $this->client->post($this->socketPushHost, [
            'form_params' => [
                'type' => 'publish',
                'event' => 'new_message',
                'to' => $message->getReceiver()->getUsername(),
                'data' => [
                    'ret' => 1,
                    'msg' => 'Hello, You got new message, please check your inbox',
                    'data' => [
                        'sender_username' => $message->getSender()->getUsername(),
                        'sender' => $message->getSender()->getNickname(),
                        'createdAt' => $this->globalValue->ago($message->getCreatedAt()),
                        'content' => mb_substr(strip_tags($message->getContent()), 0, 20),
                    ]
                ]
            ]
        ]);

        $this->container->get(NoticeService::class)->emailMessage(
            $message->getReceiver()->getEmail(),
            $message
        );
    }

    public function pushCreateBlogEvent($username, $msg, $ret = 1, $percent = null)
    {
        $this->client->post($this->socketPushHost, [
            'form_params' => [
                'type' => 'publish',
                'event' => 'create_blog_event',
                'to' => $username,
                'data' => [
                    'ret' => $ret,
                    'msg' => $msg,
                    'percent' => $percent
                ]
            ]
        ]);
    }

    public function pushNewFollowerNotification(User $from, User $followed)
    {
        $this->client->post($this->socketPushHost , [
            'form_params' => [
                'type' => 'publish',
                'event' => 'new_follower',
                'to' => $followed->getUsername(),
                'data' => [
                    'ret' => 1,
                    'msg' => 'Hello, You have new follower, please check your inbox',
                    'data' => [
                        'followerNickname' => $from->getNickname(),
                        'followerUsername' => $from->getUsername(),
                        'createdAt' => $this->globalValue->ago(new \DateTime()),
                    ]
                ]
            ]
        ]);
    }
}
