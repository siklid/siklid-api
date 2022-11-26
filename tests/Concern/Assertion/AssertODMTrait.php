<?php

declare(strict_types=1);

namespace App\Tests\Concern\Assertion;

/**
 * This trait contains assertions related to the ODM.
 */
trait AssertODMTrait
{
    /**
     * Asserts that the given document exists in the database.
     */
    protected function assertExists(string $class, array $criteria): void
    {
        $repository = $this->getRepository($class);
        $object = $repository->findOneBy($criteria);
        $this->assertNotNull($object, 'Failed asserting that the document exists.');
    }

    /**
     * Asserts that the given document does not exist in the database.
     */
    protected function assertNotExists(string $class, array $criteria): void
    {
        $repository = $this->getRepository($class);
        $object = $repository->findOneBy($criteria);
        $this->assertNull($object, 'Failed asserting that the document does not exist.');
    }

    /**
     * Asserts that the given collection is empty.
     */
    protected function assertEmptyCollection(string $class): void
    {
        $repository = $this->getRepository($class);
        $objects = $repository->findAll();
        $this->assertEmpty($objects);
    }
}
