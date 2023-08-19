<?php

declare(strict_types=1);
/**
 * This file is part of friendsofhyperf/jet.
 *
 * @link     https://github.com/friendsofhyperf/jet
 * @document https://github.com/friendsofhyperf/jet/blob/4.x/README.md
 * @contact  huangdijia@gmail.com
 */

namespace FriendsOfHyperf\Jet\PathGenerator;

use FriendsOfHyperf\Jet\Contract\PathGeneratorInterface;

class GrpcPathGenerator implements PathGeneratorInterface
{
    public function generate(string $service, string $method): string
    {
        return '/' . $method;
    }
}
