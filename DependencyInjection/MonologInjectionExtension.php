<?php

namespace Raketman\Bundle\MonologInjectionBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class MonologInjectionExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $container->setParameter(
            'raketman.logger.directories',
            (isset($config['directories']) && is_array($config['directories']) && count($config['directories'])) ? $config['directories'] : [$container->getParameter('kernel.root_dir') . '/../src']
        );
    }
}
