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
use FriendsOfHyperf\Jet\MetadataManager;

require_once __DIR__ . '/../src/autoload.php';

MetadataManager::register('jsonrpc', $metadata = new Metadata());

assert(MetadataManager::get('jsonrpc') instanceof Metadata);
assert(MetadataManager::get('jsonrpc') !== $metadata);
assert(MetadataManager::get('jsonrpc2') === null);
