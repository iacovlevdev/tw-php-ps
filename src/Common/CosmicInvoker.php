<?php

namespace WebTeam\Demo\CosmicSystems\Common;

use Spiral\RoadRunner\GRPC\ContextInterface;
use Spiral\RoadRunner\GRPC\InvokerInterface;
use Spiral\RoadRunner\GRPC\Method;
use Spiral\RoadRunner\GRPC\ServiceInterface;
use Throwable;
use WebTeam\Demo\Cosmic\Proto\ErrorResponse;

class CosmicInvoker implements InvokerInterface
{
    public function __construct(readonly private InvokerInterface $invoker)
    {
    }

    public function invoke(ServiceInterface $service, Method $method, ContextInterface $ctx, ?string $input): string
    {
        try {
            return $this->invoker->invoke($service, $method, $ctx, $input);
        } catch (Throwable) {
            return (new ErrorResponse())->serializeToString();
        }
    }
}