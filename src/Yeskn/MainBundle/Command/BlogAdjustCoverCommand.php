<?php

/**
 * This file is part of project wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-30 22:19:17
 */

namespace Yeskn\MainBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Yeskn\Support\Command\AbstractCommand;

class BlogAdjustCoverCommand extends AbstractCommand
{
    protected function configure()
    {
        $this->setName('blog:adjust-cover');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $blogs = $this->doctrine()->getRepository('YesknMainBundle:Blog')->findAll();

        foreach ($blogs as $blog) {
            $blogName = $blog->getName();
            $this->connection()->executeQuery("use wpcast_{$blogName}");
            $queryBuilder = $this->connection()->createQueryBuilder();

            $result = $queryBuilder->from('wp_options')
                ->select('option_value')
                ->where("option = 'template'")
                ->execute();

            $result->fetchAll(\PDO::FETCH_ASSOC);
        }
    }
}