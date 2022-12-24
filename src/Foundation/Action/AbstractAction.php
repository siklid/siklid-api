<?php

declare(strict_types=1);

namespace App\Foundation\Action;

use App\Siklid\Document\User;

/**
 * Base action class.
 * Each use case in the application layer should extend this class.
 * It extends the Symfony AbstractController to provide access to the container
 * besides the use case specific methods.
 *
 * @method User getUser() - Returns the current user.
 */
abstract class AbstractAction implements ActionInterface
{
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
