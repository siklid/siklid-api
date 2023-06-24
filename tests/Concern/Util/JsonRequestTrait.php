<?php

declare(strict_types=1);

namespace App\Tests\Concern\Util;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * This trait is used to send JSON requests using the Symfony client.
 *
 * @mixin WebTestCase
 */
trait JsonRequestTrait
{
    protected function jsonRequest(KernelBrowser $client, string $method, string $uri, array $data = [], array $headers = []): void
    {
        $client->request(
            $method,
            $uri,
            [],
            [],
            array_merge($headers, ['CONTENT_TYPE' => 'application/json']),
            json_encode($data)
        );
    }

    protected function getJson(KernelBrowser $client, string $uri, array $headers = []): void
    {
        $this->jsonRequest($client, 'GET', $uri, [], $headers);
    }

    protected function postJson(KernelBrowser $client, string $uri, array $data = [], array $headers = []): void
    {
        $this->jsonRequest($client, 'POST', $uri, $data, $headers);
    }

    protected function putJson(KernelBrowser $client, string $uri, array $data = [], array $headers = []): void
    {
        $this->jsonRequest($client, 'PUT', $uri, $data, $headers);
    }

    protected function pathJson(KernelBrowser $client, string $uri, array $data = [], array $headers = []): void
    {
        $this->jsonRequest($client, 'PATCH', $uri, $data, $headers);
    }

    protected function deleteJson(KernelBrowser $client, string $uri, array $headers = []): void
    {
        $this->jsonRequest($client, 'DELETE', $uri, [], $headers);
    }
}
