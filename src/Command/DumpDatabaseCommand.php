<?php
/**
 * This file is part of the examples package.
 *
 * (c) Daniel Gomes <me@danielcsgomes.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DumpDatabaseCommand extends BaseCommand
{
    protected function configure()
    {
        $this
            ->setName('database:dump')
            ->setDescription('Dump the database.')
            ->addOption('skip-lock-tables')
            ->addOption('add-drop-database')
            ->addOption('add-drop-table')
            ->setHelp(
                'The <info>database:dump</info> will dump all tables.'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filename = sprintf(
            '%s/tmp/dump_%s.sql',
            $this->getApplication()->getBaseDir(),
            date('d-m-Y_hs')
        );
        touch($filename);

        $dbUser = $this->container->getParameter('database.user');
        $dbPassword = $this->container->getParameter('database.password');
        $dbHost = $this->container->getParameter('database.host');
        $dbNames = implode(' ', $this->container->getParameter('backup.databases'));

        $options = $this->getOptions($input);

        $mysqldump = <<<EOF
mysqldump -u{$dbUser} -p{$dbPassword} -h{$dbHost} {$options} --databases {$dbNames} > {$filename}
EOF;
        exec($mysqldump);
    }

    private function getOptions(InputInterface $input)
    {
        $options = [];

        if ($input->hasOption('add-drop-database')) {
            $options[] = '--add-drop-database';
        }
        if ($input->hasOption('add-drop-table')) {
            $options[] = '--add-drop-table';
        }
        if ($input->hasOption('skip-lock-tables')) {
            $options[] = '--skip-lock-tables';
        }

        return implode(' ', $options);
    }
} 
