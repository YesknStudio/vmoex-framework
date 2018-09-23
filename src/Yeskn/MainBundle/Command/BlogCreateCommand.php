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

    /**
     * @var OutputInterface
     */
    private $output;

    private $username;

    protected function configure()
    {
        $this->setName('blog:create');
        $this->addOption('username', null, InputOption::VALUE_REQUIRED);
        $this->addOption('password', null, InputOption::VALUE_REQUIRED);
        $this->addOption('email', null, InputOption::VALUE_REQUIRED);
        $this->addOption('blogName', null, InputOption::VALUE_REQUIRED);
        $this->addOption('domain', null, InputOption::VALUE_REQUIRED);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Doctrine\DBAL\DBALException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;

        $container = $this->getContainer();
        $allocate = new AllocateSpaceService($container);

        /** @var \Doctrine\DBAL\Connection $connection */
        $connection = $container->get('doctrine')->getConnection();
        $sm = $connection->getSchemaManager();

        $this->username = $username = $input->getOption('username');
        $password = $input->getOption('password');
        $blogName = $input->getOption('blogName');
        $email = $input->getOption('email');
        $domain = $input->getOption('domain');

        $dbName = 'wpcast_'.$domain;

        $sm->createDatabase($dbName);

        $this->writeln('创建数据库成功...', 30);

        $sql = "grant all privileges on {$dbName}.* to {$domain}@localhost identified by '?';flush privileges;";

        $statement = $connection->prepare($sql);
        $statement->execute([$password]);

        $this->writeln('创建数据库用户成功...', 30);

        $webPath = $allocate->allocateWebSpace($domain);

        $this->writeln('分配服务器空间成功...', 40);

        $allocate->allocateDbSpace($domain);

        $this->writeln('分配数据库空间成功...', 50);

        $fs = new Filesystem();

        $config = $container->getParameter('wpcast');

        $fs->mirror($config['wordpress_source'], $webPath);

        $this->writeln('复制wordpress代码成功...', 60);

        $fs->chown($webPath, $config['server_user'], true);

        $this->initDatabase($domain, $username, $blogName, $password, $email);

        $this->writeln('博客初始化成功！', 70);

        $this->writeln(sprintf('地址：<a target="_blank" href="%s">%s</a>', $this->url, $this->url));
        $this->writeln('用户名：'. $username);
        $this->writeln('标题：'. $blogName);
        $this->writeln('密码：'. $password);
        $this->writeln('邮箱：'. $email);

        $this->writeln('您的博客创建成功', 100);
    }

    public function initDatabase($domain, $username, $title, $pass, $email)
    {
        $this->url = $url = "https://{$domain}." . $this->getContainer()->getParameter('domain');
        $client = new Client(['verify' => false]);

        $client->post($url . '/wp-admin/setup-config.php?step=2', [
            'form_params' => [
                'dbname' => 'wpcast_'.$domain,
                'uname' => $domain,
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
                'user_name' => $username,
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

    protected function writeln($msg, $percent = null)
    {
        $pushService = $this->getContainer()->get('socket.push');

        $this->output->writeln($msg);

        $pushService->pushCreateBlogEvent($this->username, $msg, $percent);
    }
}