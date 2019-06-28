<?php

namespace App\Services;

use Exception;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class LoggerFactory
{
    /**
     * @param string $name
     * @param $stream
     *
     * @return Logger
     *
     * @throws Exception
     */
    public function create(string $name, $stream)
    {
        $logger = new Logger($name);
        $logger->pushHandler(new StreamHandler($stream));

        return $logger;
    }
}
