<?php

namespace Raketman\Bundle\MonologInjectionBundle;

use Raketman\Bundle\MonologInjectionBundle\DependencyInjection\Compiler\LoggerInjectionPass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class MonologInjectionBundle extends Bundle
{
    /** @var ContainerBuilder */
    protected $container;

    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new LoggerInjectionPass());
    }

    public function boot()
    {
        parent::boot();
        // Создади сервис, чтобы вызвать все методы, которые мы собрали на этапе компиляции
        $this->container->get('raketman.logger.storage');

    }
}
