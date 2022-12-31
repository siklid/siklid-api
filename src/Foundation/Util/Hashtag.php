<?php

declare(strict_types=1);

namespace App\Foundation\Util;

final class Hashtag
{
    public function extract(string $text): array
    {
        $hashtags = [];
        preg_match_all('/(#[\p{L}\d_]+)/u', $text, $matches);

        foreach ($matches[0] as $match) {
            $hashtags[] = $match;
        }

        return $hashtags;
    }
}
