<?php

namespace App\Tests\Services;

use App\Services\LoggerFactory;
use InvalidArgumentException;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;

class LoggerFactoryTest extends TestCase
{
    public function testCreateOK()
    {
        $factory = new LoggerFactory();

        $logger = $factory->create('test', './log-filepath');

        $this->assertInstanceOf(Logger::class, $logger);
    }

    public function testCreateInvalidStream()
    {
        $factory = new LoggerFactory();

        $this->expectException(InvalidArgumentException::class);

        $factory->create('test', false);
    }
}
