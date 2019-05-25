<?php

namespace Raketman\Bundle\MonologInjectionBundle\Services;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;


final class LoggerStorage
{
    /** @var  LoggerInterface[] */
    private static $loggers = [];

    /**
     * @param $class
     * @return LoggerInterface|NullLogger
     */
    public static function getLogger($class)
    {
        if (false === isset(self::$loggers[$class])) {
            return new NullLogger();
        }

        $logger = self::$loggers[$class];
        return $logger;
    }

    public function addLogger($class, $logger)
    {
        self::$loggers[$class] = $logger;
    }

}
