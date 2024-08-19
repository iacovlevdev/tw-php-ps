<?php

namespace WebTeam\Demo\CosmicSystems\Providers\Lyra;

use WebTeam\Demo\Cosmic\Proto\StatusRequest;

class LyraApi
{
    private const METHOD_CHARGE = 'Charge';
    private const METHOD_STATUS = 'Status';

    private const API_URL = 'https://lyra.wtstage.lol/api/v1';

    public function __construct(
        readonly private LyraConfig $configuration
    ) {
    }

    public function status(StatusRequest $request): array
    {
        $data = [
            'Username' => 'demo',
            'OrderId' => $request->getRequestId(),
            'Client' => 'desktop',
            'Attributes' => null
        ];

        return $this->post(self::METHOD_STATUS, $data);
    }

    public function charge(): array
    {
        $data = [
            'Username' => 'demo',
            'Client' => 'desktop',
            'NotificationURL' => 'https://gate.wtstage.lol/api/v1/notification',
            'MessageID' => rand(1, 999999),
        ];

        return $this->post(self::METHOD_CHARGE, $data);
    }

    public function init(): void
    {
        $this->configuration->client()->request('GET', self::API_URL . '/init');
    }

    private function post(string $method, array $data): array
    {
        $uuid = $this->configuration->generateUuid();
        $payload = json_encode([
            'method' => $method,
            'params' => [
               'Signature' => $this->configuration->sign($method, $uuid, $data),
               'UUID'      => $uuid,
               'Data'      => $data
            ]
        ]);

        $response = $this->configuration->client()->post(
            self::API_URL,
            [
                'headers' => ['Content-Type' => 'application/json'],
                'body'    => $payload
            ]
        );

        return json_decode($response->getBody(), true);
    }
}