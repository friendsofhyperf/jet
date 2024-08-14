<?php

/*
 * This file is part of friendsofhyperf/jet.
 *
 * @link     https://github.com/friendsofhyperf/jet
 * @document https://github.com/friendsofhyperf/jet/blob/main/README.md
 * @contact  huangdijia@gmail.com
 */

use FriendsOfHyperf\Jet\Client;
use FriendsOfHyperf\Jet\Metadata;
use FriendsOfHyperf\Jet\ProtocolManager;

require_once __DIR__ . '/../src/autoload.php';

ProtocolManager::register('jsonrpc', $metadata = new Metadata());

assert($metadata instanceof Metadata);
assert(ProtocolManager::get('jsonrpc2') === null);
assert(ProtocolManager::getService('CalculatorService', 'jsonrpc') instanceof Client);
try {
    ProtocolManager::getService('CalculatorService', 'jsonrpc2');
} catch (\Exception $e) {
    assert($e->getMessage() === 'Protocol jsonrpc2 undefined.');
}
