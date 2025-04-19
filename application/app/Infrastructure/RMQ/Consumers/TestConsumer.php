<?php

namespace App\Infrastructure\RMQ\Consumers;

use App\Infrastructure\RMQ\Base\AConsumer;
use App\Infrastructure\RMQ\Base\EReject;
use App\Infrastructure\RMQ\Base\IPublisher;
use App\Infrastructure\RMQ\Packages\TestPackage;
use App\Payloads\TestPayload;
use App\Validators\TestValidator;
use Throwable;

class TestConsumer extends AConsumer
{
    public function __construct(
        private readonly IPublisher $publisher
    ) {
    }

    protected string $exchangeName = 'AGGREGATOR.test';
    protected string $queueName = 'q.test';
    protected string $routingKey = 'rk.test';


    /**
     * @throws Throwable
     * @throws EReject
     */
    public function handle(): void
    {
        //Отправляем в очередь {"id":"ss"}
        $dto = $this->getValidator()->getDTO();
        var_dump($dto);
        echo "test\n";
        // Следующий пакет не пройдет валидацию в текущем консьюмере
        $this->publisher->send(new TestPackage(new TestPayload($dto)));
    }

    public function getValidator(): TestValidator
    {
        return $this->validator;
    }

    public function setValidator(): void
    {
        $this->validator = new TestValidator($this->body);
    }
}

