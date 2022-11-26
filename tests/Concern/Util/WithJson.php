<?php

declare(strict_types=1);

namespace App\Tests\Concern\Util;

use App\Foundation\Util\Json;

/**
 * A trait for tests that need to work with JSON.
 */
trait WithJson
{
    protected Json $json;

    /**
     * Custom template method to set up the JSON utility.
     */
    protected function setUpUtils(): void
    {
        $this->json = new Json();
    }

    /**
     * Returns the JSON utility.
     */
    protected function json(): Json
    {
        return $this->json;
    }
}
