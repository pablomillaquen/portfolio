<?php

namespace App\Support;

class TranslatableContent
{
    public static function text(mixed $value, string $locale): mixed
    {
        if (! is_array($value)) {
            return $value;
        }

        return $value[$locale] ?? $value['en'] ?? $value['es'] ?? reset($value);
    }

    public static function deep(mixed $value, string $locale): mixed
    {
        if (! is_array($value)) {
            return $value;
        }

        if (array_key_exists('en', $value) || array_key_exists('es', $value)) {
            return self::text($value, $locale);
        }

        return array_map(fn ($item) => self::deep($item, $locale), $value);
    }
}
