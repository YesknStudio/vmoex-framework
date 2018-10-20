<?php

/**
 * This file is part of project wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-30 22:19:17
 */

namespace Yeskn\MainBundle\Command;

use Predis\Client;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\File\File;
use Yeskn\MainBundle\Entity\Blog;
use Yeskn\Support\Command\AbstractCommand;
use Intervention\Image\ImageManagerStatic as Image;

class BlogAdjustCoverCommand extends AbstractCommand
{
    protected function configure()
    {
        $this->setName('blog:adjust-cover');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $blogs = $this->doctrine()->getRepository('YesknMainBundle:Blog')
            ->findBy(['status' => Blog::STATUS_CREATED]);

        foreach ($blogs as $blog) {
            $this->connection()->executeQuery("use wpcraft");
            $blogName = $blog->getSubdomain();

            $this->connection()->executeQuery("use wpcast_{$blogName}");
            $queryBuilder = $this->connection()->createQueryBuilder();

            $result = $queryBuilder->from('wp_options')
                ->select('option_value')
                ->where("option_name = 'template'")
                ->execute();

            $result = $result->fetchAll(\PDO::FETCH_ASSOC);

            if (empty($result) || empty($result[0])) {
                continue ;
            }

            $template = $result[0]['option_value'];

            /** @var Client $redis */
            $redis = $this->get('snc_redis.default');

            $redisTheme = $redis->get("blog:{$blogName}:theme");

            if (empty($redisTheme) || $redisTheme != $template) {
                $redis->set("blog:{$blogName}:theme", $template);
            } else if ($redisTheme == $template) {
                continue;
            }

            $wpcast = $this->parameter('wpcast');

            $blogPath = $wpcast['web_path'] . '/' . $blogName;

            $files = [
                "/wp-content/themes/{$template}/screenshot.png",
                "/wp-content/themes/{$template}/screenshot.jpg",
            ];

            foreach ($files as $file) {
                if (file_exists($blogPath . $file)) {
                    $fileObj = new File($blogPath . $file);
                    $ext = $fileObj->guessExtension();

                    Image::configure(array('driver' => 'gd'));

                    if (!is_dir($this->parameter('kernel.project_dir') . '/web/upload/blog')) {
                        mkdir($this->parameter('kernel.project_dir') . '/web/upload/blog');
                    }

                    $relative = 'upload/blog/' . $blogName .  time() . '.' . $ext;
                    $newFile = $this->parameter('kernel.project_dir') . '/web/' . $relative;

                    copy($blogPath . $file, $newFile);

                    $image = Image::make($newFile);
                    $image->resize(325, 225)->save();

                    $img = $this->parameter('assets_base_url') . '/' . $relative;
                    break;
                }
            }

            if (empty($img)) {
                continue ;
            }

            $this->connection()->executeQuery("use wpcraft");
            $blog->setCover($img);

            $this->em()->flush();
        }
    }
}
