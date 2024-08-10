<?php

namespace FriendsOfHyperf\Jet;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'aspects' => [
                Aspect\GuzzleHttpTransporterAspect::class,
            ],
        ];
    }
}
