<?php

declare(strict_types=1);
/**
 * This file is part of jet.
 *
 * @link     https://github.com/friendsofhyperf/jet
 * @document https://github.com/friendsofhyperf/jet/3.x/main/README.md
 * @contact  Huangdijia@gmail.com
 */
use Huangdijia\PhpCsFixer\Config;

require __DIR__ . '/vendor/autoload.php';

return (new Config())
    ->setHeaderComment(
        projectName: 'jet',
        projectLink: 'https://github.com/friendsofhyperf/jet',
        projectDocument: 'https://github.com/friendsofhyperf/jet/3.x/main/README.md',
        contacts: [
            'Huangdijia@gmail.com',
        ],
    )
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->exclude('public')
            ->exclude('runtime')
            ->exclude('vendor')
            ->in(__DIR__)
            ->append([
                __FILE__,
            ])
    )
    ->setUsingCache(false);
