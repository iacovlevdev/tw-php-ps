<?php

namespace WebTeam\Demo\CosmicSystems\Providers\Lyra\Commands;

use WebTeam\Demo\Cosmic\Proto\Status;
use WebTeam\Demo\Cosmic\Proto\StatusRequest;
use WebTeam\Demo\Cosmic\Proto\StatusResponse;
use WebTeam\Demo\CosmicSystems\Common\Command\CommandInterface;
use WebTeam\Demo\CosmicSystems\Providers\Lyra\LyraApi;
use WebTeam\Demo\CosmicSystems\Providers\Lyra\LyraConfig;

class Inquiry implements CommandInterface
{
    public function __construct(
        readonly  private LyraConfig       $configuration,
        readonly  private StatusRequest    $request
    ) {}

    public function execute(): StatusResponse
    {
        $api = new LyraApi($this->configuration);
        $api->init();
        $result = $api->status($this->request);

        $response = new StatusResponse();
        $response->setStatus(
            match ($result['status']) {
                'in_progress' =>  Status::BROADCASTING,
                'closed' =>  Status::SCHEDULING,
                default => Status::UNDEFINED,
            }
        );
        $response->setBattery($result['battery']);

        return $response;
    }
}