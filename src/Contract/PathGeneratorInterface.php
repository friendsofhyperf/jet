<?php

/*
 * This file is part of friendsofhyperf/jet.
 *
 * @link     https://github.com/friendsofhyperf/jet
 * @document https://github.com/friendsofhyperf/jet/blob/main/README.md
 * @contact  huangdijia@gmail.com
 */

namespace FriendsOfHyperf\Jet\Contract;

interface PathGeneratorInterface
{
    /**
     * @param string $service
     * @param string $method
     * @return string
     */
    public function generate($service, $method);
}
