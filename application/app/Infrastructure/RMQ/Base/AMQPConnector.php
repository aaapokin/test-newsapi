<?php

namespace App\Infrastructure\RMQ\Base;

use App\Infrastructure\RMQ\Base\DTO\CfgDTO;
use App\Services\Env;
use Illuminate\Support\Facades\Log;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use RuntimeException;
use Throwable;

class AMQPConnector implements IConnection
{
    protected static ?AMQPStreamConnection $instance = null;

    private function __construct()
    {
    }

    private function __clone()
    {
    }


    public function __wakeup()
    {
        throw new RuntimeException("Cannot unserialize a singleton.");
    }

    protected static function cfg(): CfgDTO
    {
        $rmqCFG = app()->make(Env::class)->getRmqCFG();
        return new CfgDTO(
            amqp_hosts: $rmqCFG->amqp_hosts,
            amqp_user: $rmqCFG->amqp_user,
            amqp_password: $rmqCFG->amqp_password,
            amqp_vhost: $rmqCFG->amqp_vhost,
            amqp_port: $rmqCFG->amqp_port
        );
    }

    /**
     * @throws Throwable
     */
    public static function getInstance(): AMQPStreamConnection
    {
        if (!self::$instance?->isConnected()) {
            self::$instance = null;
        }

        if (self::$instance === null) {
            $rmqCFG = self::cfg();
            for ($i = 0; $i < 5; $i++) {
                $flagERR = false;
                try {
                    $host = $rmqCFG->amqp_hosts[$i % count($rmqCFG->amqp_hosts)];
                    self::$instance = new AMQPStreamConnection(
                        host: $host,
                        port: $rmqCFG->amqp_port,
                        user: $rmqCFG->amqp_user,
                        password: $rmqCFG->amqp_password,
                        vhost: $rmqCFG->amqp_vhost,
//                        insist: false,
//                        login_method: 'AMQPLAIN',
//                        login_response: null,
//                        locale: 'en_US',
                        connection_timeout: 3.0,
                        read_write_timeout: 130.0,
//                        context: false,
//                        keepalive: false,
                        heartbeat: 60
                    );
                    break;
                } catch (Throwable $e) {
                    Log::error(self::class . " $host connect fail " . $e->getMessage());
                    $flagERR = true;
                }
            }
            if ($flagERR) {
                Log::error(self::class . " connect fail CRITICAL");
                throw new \RuntimeException(self::class . " connect fail CRITICAL");
            }
        }
        return self::$instance;
    }
}
