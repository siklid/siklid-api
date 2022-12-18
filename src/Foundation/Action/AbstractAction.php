<?php

declare(strict_types=1);

namespace App\Foundation\Action;

use App\Siklid\Document\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Base action class.
 * Each use case in the application layer should extend this class.
 * It extends the Symfony AbstractController to provide access to the container
 * besides the use case specific methods.
 *
 * @method User getUser() - Returns the current user.
 */
abstract class AbstractAction extends AbstractController implements ActionInterface
{
    /**
     * Returns the value of the given parameter.
     *
     * @return mixed The value of the parameter
     *
     * @psalm-suppress PossiblyUndefinedMethod - The user should know about the config values
     * @psalm-suppress MixedAssignment - Expected to be mixed
     */
    public function getConfig(string $key, mixed $default = null): mixed
    {
        $keyParts = explode('.', $key);
        $config = $this->getParameter($keyParts[0]);

        for ($i = 1, $iMax = count($keyParts); $i < $iMax; ++$i) {
            if (! isset($config[$keyParts[$i]])) {
                return $default;
            }
            assert(is_array($config));
            $config = $config[$keyParts[$i]];
        }

        return $config ?? $default;
    }

    /**
     * Creates a new form instance from the given type.
     *
     * @template       T
     *
     * @param class-string<T>      $class
     * @param array<string, mixed> $data
     *
     * @psalm-suppress MixedMethodCall - The user should know about the class type
     * @psalm-suppress MixedAssignment - Expected to be mixed
     *
     * @return T
     */
    public function fill(string $class, array $data): object
    {
        $object = new $class();

        foreach ($data as $key => $value) {
            $setter = 'set'.ucfirst($key);
            if (method_exists($object, $setter)) {
                $object->$setter($value);
            }
        }

        return $object;
    }
}
