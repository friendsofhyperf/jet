<?php

declare(strict_types=1);
/**
 * This file is part of jet.
 *
 * @link     https://github.com/friendsofhyperf/jet
 * @document https://github.com/friendsofhyperf/jet/blob/main/README.md
 * @contact  Huangdijia@gmail.com
 */

namespace FriendsOfHyperf\Jet\PathGenerator;

use FriendsOfHyperf\Jet\Contract\PathGeneratorInterface;

use function FriendsOfHyperf\Jet\str_replace_array;
use function FriendsOfHyperf\Jet\str_studly;

class DotPathGenerator implements PathGeneratorInterface
{
    public function generate(string $service, string $method): string
    {
        $handledNamespace = explode('\\', $service);
        $handledNamespace = str_replace_array('\\', ['/'], end($handledNamespace));
        $path = str_studly($handledNamespace);

        return $path . '.' . str_studly($method);
    }
}
