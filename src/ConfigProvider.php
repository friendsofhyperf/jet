<?php

declare(strict_types=1);
/**
 * This file is part of jet.
 *
 * @link     https://github.com/friendsofhyperf/jet
 * @document https://github.com/friendsofhyperf/jet/3.x/main/README.md
 * @contact  Huangdijia@gmail.com
 */

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
