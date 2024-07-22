<?php

namespace WebTeam\Demo\CosmicSystems\Providers\Lyra\Commands;

use Google\Protobuf\Internal\Message;
use Spiral\RoadRunner\GRPC\ContextInterface;
use WebTeam\Demo\Cosmic\Proto\StatusRequest;
use WebTeam\Demo\CosmicSystems\Common\Command\CommandInterface;
use WebTeam\Demo\CosmicSystems\Providers\Lyra\LyraApi;
use WebTeam\Demo\CosmicSystems\Providers\Lyra\LyraConfig;

class Charge implements CommandInterface
{
    public function __construct(
        readonly  private LyraConfig       $configuration,
        readonly  private StatusRequest    $request,
        readonly  private ContextInterface $context
    ) {}

    public function execute(): Message
    {
        $api = new LyraApi($this->configuration);
        $api->init();
        $result = $api->charge();

        return new Message(['status' => $result['status']]);
    }
}