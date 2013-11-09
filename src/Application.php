<?php
/**
 * This file is part of the examples package.
 *
 * (c) Daniel Gomes <me@danielcsgomes.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Command\DumpDatabaseCommand;
use Command\UploadToDropboxCommand;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class Application extends BaseApplication implements ContainerAwareInterface
{
    const VERSION = '0.0.x';
    /**
     * @var string
     */
    protected $baseDir;
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct()
    {
        $this->baseDir = __DIR__ . '/..';

        parent::__construct('Console App', self::VERSION);

        $this->loadContainerDefinitions();

        $this->add(new DumpDatabaseCommand());
        $this->add(new UploadToDropboxCommand());
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Loads and configures the DI Container definitions
     */
    private function loadContainerDefinitions()
    {
        $configDirectories = array(
            __DIR__ . '/../config'
        );

        $container = include __DIR__ . '/container.php';

        $loader = new YamlFileLoader($container, new FileLocator($configDirectories));
        $loader->load('parameters.yaml');

        $this->setContainer($container);
    }

    /**
     * @return string
     */
    public function getBaseDir()
    {
        return $this->baseDir;
    }
} 
