<?php

namespace App\Infrastructure\RMQ\Base;

interface IPublisher
{
    public function send(IPackage $package): void;
}
