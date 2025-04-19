<?php

namespace App\Infrastructure\RMQ\Base;

use App\Infrastructure\RMQ\Base\DTO\CfgDTO;
use App\Services\Env;

class AMQPConnectorELK extends AMQPConnector
{
    protected static function cfg(): CfgDTO
    {
        $rmqCFG = app()->make(Env::class)->getRmqCFG();
        return new CfgDTO(
            amqp_hosts: $rmqCFG->amqp_elk_hosts,
            amqp_user: $rmqCFG->amqp_elk_user,
            amqp_password: $rmqCFG->amqp_elk_password,
            amqp_vhost: $rmqCFG->amqp_elk_vhost,
            amqp_port: $rmqCFG->amqp_elk_port
        );
    }
}
