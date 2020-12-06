<?php

namespace Raketman\Bundle\MonologInjectionBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        if (\Symfony\Component\HttpKernel\Kernel::MAJOR_VERSION > 4) {
            $treeBuilder = new TreeBuilder('monolog_injection');
            $rootNode = $treeBuilder->getRootNode();
        } else {
            $treeBuilder = new TreeBuilder();
            $rootNode = $treeBuilder->root('monolog_injection');
        }
        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.
        $rootNode->children()
            ->arrayNode('directories')
                ->prototype('scalar')->end()
                ->info('Массив directories, которые попадут в обработку')
            ->end();

        return $treeBuilder;
    }
}
