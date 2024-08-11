<?php

declare(strict_types=1);
/**
 * This file is part of friendsofhyperf/jet.
 *
 * @link     https://github.com/friendsofhyperf/jet
 * @document https://github.com/friendsofhyperf/jet/blob/main/README.md
 * @contact  huangdijia@gmail.com
 */

namespace FriendsOfHyperf\Jet\PathGenerator;

use FriendsOfHyperf\Jet\Contract\PathGeneratorInterface;

use function FriendsOfHyperf\Jet\str_snake;

class PathGenerator implements PathGeneratorInterface
{
    public function generate(string $service, string $method): string
    {
        $handledNamespace = explode('\\', $service);
        $handledNamespace = str_replace('\\', '/', end($handledNamespace));
        $handledNamespace = str_replace('Service', '', $handledNamespace);
        $path = str_snake($handledNamespace);

        if ($path[0] !== '/') {
            $path = '/' . $path;
        }

        return $path . '/' . $method;
    }
}
