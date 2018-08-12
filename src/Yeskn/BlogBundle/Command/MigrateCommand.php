<?php
/**
 * This file is part of project Vmoex.
 *
 * Author: Jake
 * Create: 2018-05-27 20:59:13
 */

namespace Yeskn\BlogBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Yeskn\BlogBundle\Entity\Post;

class MigrateCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('migrate');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getDoctrine()->getManager();

        $connection = $this->getContainer()->get('doctrine.dbal.spider_connection');

        $id = 0;
        while ($ret = $connection->query("select * from document where id > {$id} limit 1")->fetch(\PDO::FETCH_ASSOC)) {

            $ids = [1, 3, 4, 5];
            $id = $ids[rand(0, 3)];
            $author = $this->getDoctrine()->getRepository('YesknBlogBundle:User')->find($id);

            $post = new Post();

            $post->setContent($ret['content']);
            $post->setTitle($ret['title']);
            $post->setCreatedAt(new \DateTime($ret['create_time']));
            $post->setStatus('published');
            $post->setAuthor($author);
            $post->setCover('');
            $post->setExcerpt($ret['description']);
            $post->setIsDeleted(false);
            $post->setIsTop(false);

            $em->persist($post);
            $em->flush();

            $output->writeln('success'. $ret['title']);

            $id = $ret['id'];
        }
    }


    /**
     * @return \Doctrine\Bundle\DoctrineBundle\Registry|object
     */
    public function getDoctrine()
    {
        return $this->getContainer()->get('doctrine');
    }
}