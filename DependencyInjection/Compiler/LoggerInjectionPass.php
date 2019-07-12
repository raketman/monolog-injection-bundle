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
                // Получим полное название класса
                $className = str_replace(
                    "/",
                    "\\",
                    sprintf('%s/%s',
                        $this->extractNamespace($file->getPathname()),
                        explode('.', $file->getBasename())[0]
                    )
                );
                
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

    private function extractNamespace($file) {
        $ns = NULL;
        $handle = fopen($file, "r");
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                if (strpos($line, 'namespace') === 0) {
                    $parts = explode(' ', $line);
                    $ns = rtrim(trim($parts[1]), ';');
                    break;
                }
            }
            fclose($handle);
        }
        return $ns;
    }
}
