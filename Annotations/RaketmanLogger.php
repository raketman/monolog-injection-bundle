<?php

namespace Raketman\Bundle\MonologInjectionBundle\Annotations;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * Class RaketmanLogger
 * @package Raketman\Bundle\MonologInjectionBundle\Annotations
 */
final class RaketmanLogger
{
    private $attr;

    public function __construct(array $attr)
    {
        $this->attr = $attr;
    }

    public function getLoggerServiceName()
    {
        return false === isset($this->attr['value']) ? 'logger' : sprintf('monolog.logger.%s', $this->attr['value']);
    }
}
