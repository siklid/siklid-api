<?php

declare(strict_types=1);

namespace App\Foundation\Action;

use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use UnitEnum;

class Config implements ConfigInterface
{
    private ParameterBagInterface $config;

    public function __construct(ParameterBagInterface $config)
    {
        $this->config = $config;
    }

    public function all(): array
    {
        return $this->config->all();
    }

    /**
     * @psalm-suppress MixedInferredReturnType - Mixed values are expected.
     * @psalm-suppress MixedAssignment
     * @psalm-suppress MixedReturnStatement
     */
    public function get(string $key, mixed $default = null): mixed
    {
        assert(
            is_array($default) ||
            is_scalar($default) ||
            is_null($default) ||
            $default instanceof UnitEnum
        );

        if (str_starts_with($key, '@')) {
            $key = substr($key, 1);

            return $this->getConfigOrDefault($key, $default);
        }

        $keyParts = explode('.', $key);
        $config = $this->getConfigOrDefault($keyParts[0], $default);

        for ($i = 1, $iMax = count($keyParts); $i < $iMax; ++$i) {
            if ($config instanceof UnitEnum || ! isset($config[$keyParts[$i]])) {
                return $default;
            }
            assert(is_array($config));
            $config = $config[$keyParts[$i]];
        }

        return $config ?? $default;
    }

    public function has(string $key): bool
    {
        return $this->config->has($key);
    }

    /**
     * @param UnitEnum|array|scalar|null $default
     */
    private function getConfigOrDefault(string $key, mixed $default = null): mixed
    {
        try {
            return $this->config->get($key);
        } catch (InvalidArgumentException) {
            return $default;
        }
    }
}
