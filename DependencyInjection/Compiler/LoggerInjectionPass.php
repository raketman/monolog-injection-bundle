<?php

namespace Raketman\Bundle\MonologInjectionBundle\DependencyInjection\Compiler;

use Doctrine\Common\Annotations\CachedReader;
use Raketman\Bundle\MonologInjectionBundle\Services\LoggerStorage;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Raketman\Bundle\MonologInjectionBundle\Annotations\RaketmanLogger;


class LoggerInjectionPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        // Найдем все класс с аннотациями
        /** @var CachedReader $reader*/
        $reader = $container->get('annotation_reader');

        $storage = new Definition(LoggerStorage::class);
        $storage->setPublic(true);

        // Зарегаем сервис
        $container->setDefinition('raketman.logger.storage', $storage);

        $namespaces = $container->getParameter('raketman.logger.namespaces');

        // ОБработаем все зарегистрированные классы
        foreach (get_declared_classes() as $className) {
            foreach ($namespaces as $namespace) {
                if (!preg_match('/' . str_replace('/','\/', preg_quote($namespace)) . '/', $className)) {
                    continue 2;
                }
            }

            $newRef = new \ReflectionClass($className);
            /** @var RaketmanLogger $annotation */
            $annotation = $reader->getClassAnnotation($newRef, 'Raketman\Bundle\MonologInjectionBundle\Annotations\RaketmanLogger');

            if (is_null($annotation)) {
                continue;
            }

            $storage->addMethodCall('addLogger', [$className, new Reference($annotation->getLoggerServiceName())]);
        }


    }
}
