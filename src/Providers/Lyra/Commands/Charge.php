<?php

namespace WebTeam\Demo\CosmicSystems\Providers\Lyra\Commands;

use Google\Protobuf\Internal\Message;
use WebTeam\Demo\CosmicSystems\Common\Command\CommandInterface;
use WebTeam\Demo\CosmicSystems\Providers\Lyra\LyraApi;
use WebTeam\Demo\CosmicSystems\Providers\Lyra\LyraConfig;

class Charge implements CommandInterface
{
    public function __construct(
        readonly private LyraConfig $configuration
    ) {}

    public function execute(): Message
    {
        $api = new LyraApi($this->configuration);
        $api->init();
        $result = $api->charge();

        return new Message(['status' => $result['status']]);
    }
}