<?php

namespace App\Traits;

trait HasInstance
{
    public static function instance(): static
    {
        return app(static::class);
    }
}
