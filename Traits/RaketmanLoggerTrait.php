<?php

namespace Raketman\Bundle\MonologInjectionBundle\Traits;

use Psr\Log\LoggerInterface;
use Raketman\Bundle\MonologInjectionBundle\Services\LoggerStorage;

/**
 * Предоставляет метод __get, который перехватывает обращение к $logger
 * Для IDE добавлено @property-read
 *
 * Trait RaketmanLogger
 * @package Raketman\Bundle\MonologInjectionBundle\Traits
 * @property-read LoggerInterface $logger
 */
trait RaketmanLoggerTrait
{
    public function __get($name)
    {
        if ($name === 'logger') {
            return LoggerStorage::getLogger(get_called_class());
        }
    }
}
