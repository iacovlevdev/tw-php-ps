<?php

namespace WebTeam\Demo\CosmicSystems\Providers\Lyra;

use WebTeam\Demo\CosmicSystems\Common\AbstractConfiguration;
use WebTeam\Demo\CosmicSystems\Providers\Lyra\Commands\Charge;
use WebTeam\Demo\CosmicSystems\Providers\Lyra\Commands\Inquiry;

class LyraConfig extends AbstractConfiguration
{
    public function commands(): array
    {
        return [
            AbstractConfiguration::INQUIRY => Inquiry::class,
            AbstractConfiguration::CHARGE => Charge::class,
        ];
    }

    public function generateUuid(): string
    {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
           mt_rand(0, 0xffff), mt_rand(0, 0xffff),
           mt_rand(0, 0xffff),
           mt_rand(0, 0x0fff) | 0x4000,
           mt_rand(0, 0x3fff) | 0x8000,
           mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

    public function sign(string $method, string $uuid, array $data, string $key = ''): string
    {
        $plaintext = $method . $uuid . $this->serializeData($data);
        openssl_sign($plaintext, $signature, $key);

        return base64_encode($signature);
    }

    public function serializeData($object): string
    {
        if (!is_array($object)) {
            return $object;
        }

        $serialized = '';

        ksort($object);
        foreach ($object as $key => $value) {
            if (is_numeric($key)) {
                $serialized .= self::serializeData($value);
            } else {
                $serialized .= $key . self::serializeData($value);
            }
        }

        return $serialized;
    }
}