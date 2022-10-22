<?php

namespace App\Tests;

use Doctrine\ODM\MongoDB\DocumentManager;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * This trait is used to extend tests that interact with the database
 *
 * @mixin KernelTestCase
 */
trait DBTrait
{
    /**
     * Get document manager instance
     */
    protected function getDocumentManager(): DocumentManager
    {
        /** @var DocumentManager $dm */
        $dm = self::getContainer()->get('doctrine_mongodb.odm.document_manager');

        return $dm;
    }

    /**
     * Asserts that the given document exists in the database
     */
    protected function assertExists(string $class, array $criteria): void
    {
        $repository = $this->getDocumentManager()->getRepository($class);
        $object = $repository->findOneBy($criteria);
        $this->assertNotNull($object);
    }

    /**
     * Deletes all collections and indexes from the database
     */
    protected function dropAllCollections(): void
    {
        $env = self::getContainer()->getParameter('kernel.environment');

        if ('test' !== $env) {
            throw new RuntimeException('This method can only be used in the test environment');
        }

        $dm = $this->getDocumentManager();
        $sm = $dm->getSchemaManager();

        $sm->dropCollections();
    }
}
