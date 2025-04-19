<?php

namespace App\Infrastructure\RMQ\Base;

use PhpAmqpLib\Connection\AMQPStreamConnection;

interface IConnection
{
    public static function getInstance(): AMQPStreamConnection;

}
