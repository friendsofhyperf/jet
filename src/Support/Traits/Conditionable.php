<?php

declare(strict_types=1);
/**
 * This file is part of friendsofhyperf/jet.
 *
 * @link     https://github.com/friendsofhyperf/jet
 * @document https://github.com/friendsofhyperf/jet/blob/main/README.md
 * @contact  huangdijia@gmail.com
 */

namespace FriendsOfHyperf\Jet\Support\Traits;

/**
 * @template TWhenParameter
 * @template TWhenReturnType
 * @template TUnlessParameter
 * @template TUnlessReturnType
 */
trait Conditionable
{
    /**
     * Apply the callback if the given "value" is (or resolves to) truthy.
     *
     * @param (\Closure($this):TWhenParameter)|TWhenParameter $value
     * @param null|(callable($this,TWhenParameter):TWhenReturnType) $callback
     * @param null|(callable($this,TWhenParameter):TWhenReturnType) $default
     * @return $this|TWhenReturnType
     */
    public function when(mixed $value = null, ?callable $callback = null, ?callable $default = null)
    {
        $value = $value instanceof \Closure ? $value($this) : $value;

        if ($value) {
            return $callback($this, $value) ?? $this;
        }
        if ($default) {
            return $default($this, $value) ?? $this;
        }

        return $this;
    }

    /**
     * Apply the callback if the given "value" is (or resolves to) falsy.
     *
     * @param (\Closure($this):TUnlessParameter)|TUnlessParameter $value
     * @param null|(callable($this,TUnlessParameter):TUnlessReturnType) $callback
     * @param null|(callable($this,TUnlessParameter):TUnlessReturnType) $default
     * @return $this|TUnlessReturnType
     */
    public function unless(mixed $value = null, ?callable $callback = null, ?callable $default = null)
    {
        $value = $value instanceof \Closure ? $value($this) : $value;

        if (! $value) {
            return $callback($this, $value) ?? $this;
        }
        if ($default) {
            return $default($this, $value) ?? $this;
        }

        return $this;
    }
}
