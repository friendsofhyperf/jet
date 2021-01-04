<?php

declare(strict_types=1);
/**
 * This file is part of jet.
 *
 * @link     https://github.com/friendsofhyperf/jet
 * @document https://github.com/friendsofhyperf/jet/blob/main/README.md
 * @contact  huangdijia@gmail.com
 * @license  https://github.com/friendsofhyperf/jet/blob/main/LICENSE
 */
namespace FriendsOfHyperf\Jet\Packer;

use FriendsOfHyperf\Jet\Contract\PackerInterface;

class JsonLengthPacker implements PackerInterface
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var int
     */
    protected $length;

    /**
     * @var array
     */
    protected $defaultOptions = [
        'package_length_type' => 'N',
        'package_body_offset' => 4,
    ];

    public function __construct(array $options = [])
    {
        $options = array_merge($this->defaultOptions, $options);

        $this->type = $options['package_length_type'];
        $this->length = $options['package_body_offset'];
    }

    /**
     * @param mixed $data
     */
    public function pack($data): string
    {
        $data = json_encode($data);

        return pack($this->type, strlen($data)) . $data;
    }

    /**
     * @return mixed
     */
    public function unpack(string $data)
    {
        $data = substr($data, $this->length);

        if (! $data) {
            return null;
        }

        return json_decode($data, true);
    }
}
