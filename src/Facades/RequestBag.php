<?php

declare(strict_types=1);

namespace Bensedev\RequestBag\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Bensedev\RequestBag\RequestBag add(string $key, mixed $value)
 * @method static mixed get(string $key, mixed $default = null)
 * @method static bool has(string $key)
 * @method static \Bensedev\RequestBag\RequestBag remove(string $key)
 * @method static bool exists(string $key)
 * @method static array<string, mixed> all()
 * @method static \Bensedev\RequestBag\RequestBag clear()
 * @method static \Bensedev\RequestBag\RequestBag merge(array<string, mixed> $data)
 *
 * @see \Bensedev\RequestBag\RequestBag
 */
class RequestBag extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Bensedev\RequestBag\RequestBag::class;
    }
}
