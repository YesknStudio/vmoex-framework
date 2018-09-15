<?php

/**
 * This file is part of project yeskn-studio/wpcraft.
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

    public function __construct(ContainerInterface $container, $globalValue)
    {
        $this->client = new Client();
        $this->container = $container;
        $this->globalValue = $globalValue;
    }

    public function pushAll($event, $data = [])
    {
        $this->client->post($this->container->getParameter('socket_push_host') , [
            'form_params' => [
                'type' => 'publish',
                'event' => $event,
                'data' => $data
            ]
        ]);
    }

    public function pushNewMessage(Message $message)
    {
        $this->client->post($this->container->getParameter('socket_push_host') , [
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
    }

    public function pushNewFollowerNotification(User $from, User $followed)
    {
        $this->client->post($this->container->getParameter('socket_push_host') , [
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
                        'createdAt' => $this->container->get('twig.extension.global_value')
                            ->ago(new \DateTime()),
                    ]
                ]
            ]
        ]);
    }
}