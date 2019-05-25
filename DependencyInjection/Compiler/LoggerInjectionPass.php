<?php

namespace Raketman\Bundle\MonologInjectionBundle\DependencyInjection\Compiler;

use Doctrine\Common\Annotations\CachedReader;
use Raketman\Bundle\MonologInjectionBundle\Services\LoggerStorage;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Raketman\Bundle\MonologInjectionBundle\Annotations\RaketmanLogger;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;


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

        $directories = $container->getParameter('raketman.logger.directories');

        $finder = new Finder();
        $finder->files()->name('*.php')->contains('@RaketmanLogger');

        foreach ($directories as $directory) {
            /** @var SplFileInfo $file */
            foreach($finder->in($directory) as $file) {
                $className = str_replace("/", "\\", explode('.', $file->getRelativePathname())[0]);
                
                // new $className;
                $newRef = new \ReflectionClass($className);

                /** @var RaketmanLogger $annotation */
                $annotation = $reader->getClassAnnotation($newRef, 'Raketman\Bundle\MonologInjectionBundle\Annotations\RaketmanLogger');

                if (is_null($annotation)) {
                    continue;
                }

                $storage->addMethodCall('addLogger', [$newRef->getName(), new Reference($annotation->getLoggerServiceName())]);
            }
        }

    }
}
