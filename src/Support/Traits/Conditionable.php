<?php

/*
 * This file is part of friendsofhyperf/jet.
 *
 * @link     https://github.com/friendsofhyperf/jet
 * @document https://github.com/friendsofhyperf/jet/blob/main/README.md
 * @contact  huangdijia@gmail.com
 */

namespace FriendsOfHyperf\Jet\Support\Traits;

use Closure;

trait Conditionable
{
    /*
     * Apply the callback if the given "value" is (or resolves to) truthy.
     *
     * @template TWhenParameter
     * @template TWhenReturnType
     *
     * @param null|(Closure($this): TWhenParameter)|TWhenParameter $value
     * @param null|(callable($this, TWhenParameter): TWhenReturnType) $callback
     * @param null|(callable($this, TWhenParameter): TWhenReturnType) $default
     * @param null|mixed $value
     * @return $this|TWhenReturnType
     */
    public function when($value = null, $callback = null, $default = null)
    {
        $value = $value instanceof Closure ? $value($this) : $value;

        if ($value) {
            return $callback($this, $value) ?? $this;
        }
        if ($default) {
            return $default($this, $value) ?? $this;
        }

        return $this;
    }

    /*
     * Apply the callback if the given "value" is (or resolves to) falsy.
     *
     * @template TUnlessParameter
     * @template TUnlessReturnType
     *
     * @param null|(Closure($this): TUnlessParameter)|TUnlessParameter $value
     * @param null|(callable($this, TUnlessParameter): TUnlessReturnType) $callback
     * @param null|(callable($this, TUnlessParameter): TUnlessReturnType) $default
     * @param null|mixed $value
     * @return $this|TUnlessReturnType
     */
    public function unless($value = null, $callback = null, $default = null)
    {
        $value = $value instanceof Closure ? $value($this) : $value;

        if (!$value) {
            return $callback($this, $value) ?? $this;
        }
        if ($default) {
            return $default($this, $value) ?? $this;
        }

        return $this;
    }
}
