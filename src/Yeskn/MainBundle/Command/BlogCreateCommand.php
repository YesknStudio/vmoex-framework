<?php

/**
 * This file is part of project yeskn-studio/wpcraft.
 *
 * Author: Jaggle
 * Create: 2018-07-04 22:06:45
 */

namespace Yeskn\MainBundle\Command;

use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Yeskn\MainBundle\Services\AllocateSpaceService;

class BlogCreateCommand extends ContainerAwareCommand
{
    private $url;

    protected function configure()
    {
        $this->setName('blog:create');
        $this->addOption('name', null, InputOption::VALUE_REQUIRED);
        $this->addOption('password', null, InputOption::VALUE_REQUIRED);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Doctrine\DBAL\DBALException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $allocate = new AllocateSpaceService($container);

        $connection = $container->get('doctrine')->getConnection();

        $username = $input->getOption('name');
        $password = $input->getOption('password');

        $connection->executeQuery("create database wpcast_{$username};");

        $output->writeln('创建数据库成功...');

        $sql = "grant all privileges on wpcast_{$username}.* to {$username}@localhost identified by '{$password}';flush privileges;";

        $connection->executeQuery($sql);

        $output->writeln('创建数据库用户成功...');

        $webPath = $allocate->allocateWebSpace($username);

        $output->writeln('分配服务器空间成功...');

        $allocate->allocateDbSpace($username);

        $output->writeln('分配数据库空间成功...');

        $fs = new Filesystem();

        $config = $container->getParameter('wpcast');

        $fs->mirror($config['wordpress_source'], $webPath);

        $output->writeln('复制wordpress代码成功...');

        $fs->chown($webPath, $config['server_user'], true);

        $email = 'singviy@qq.com';

        $this->initDatabase($username, $username. '的博客', $password, $email);

        $output->writeln('博客初始化成功！');

        $output->writeln('地址：'. $this->url);
        $output->writeln('用户名：'. $username);
        $output->writeln('标题：'. $username . '的博客');
        $output->writeln('密码：'. $password);
        $output->writeln('邮箱：'. $email);
    }

    public function initDatabase($name, $title, $pass, $email)
    {
        $this->url = $url = "https://{$name}.wpcast.net";
        $client = new Client(['verify' => false]);

        $client->post($url . '/wp-admin/setup-config.php?step=2', [
            'form_params' => [
                'dbname' => 'wpcast_'.$name,
                'uname' => $name,
                'pwd' => $pass,
                'dbhost' => 'localhost',
                'prefix' => 'wp_',
                'language' => 'zh_CN',
                'submit' => '提交'
            ]
        ]);

        $client->post($url . '/wp-admin/install.php?step=2', [
            'form_params' => [
                'weblog_title' => $title,
                'user_name' => $name,
                'admin_password' => $pass,
                'pass1-text' => $pass,
                'admin_password2' => $pass,
                'pw_weak' => 'on',
                'admin_email' => $email,
                'Submit' => '安装WordPress',
                'language' => 'zh_CN'
            ]
        ]);
    }
}