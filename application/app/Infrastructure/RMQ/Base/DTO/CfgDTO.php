<?php

namespace App\Infrastructure\RMQ\Base\DTO;

class CfgDTO
{
    /**
     * @param string[] $amqp_hosts
     */
    public function __construct(
        public array $amqp_hosts,
        public string $amqp_user,
        public string $amqp_password,
        public string $amqp_vhost,
        public string $amqp_port,
    ) {
    }
}
