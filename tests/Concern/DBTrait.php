<?php

declare(strict_types=1);

namespace App\Tests\Concern;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;
use Doctrine\ODM\MongoDB\Repository\GridFSRepository;
use Doctrine\ODM\MongoDB\Repository\ViewRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * This trait is used to extend tests that interact with the database.
 *
 * @mixin KernelTestCase
 */
trait DBTrait
{
    /**
     * Get document manager instance.
     */
    protected function getDocumentManager(): DocumentManager
    {
        /** @var DocumentManager $dm */
        $dm = self::getContainer()->get('doctrine_mongodb.odm.document_manager');

        return $dm;
    }

    /**
     * Gets the repository for a document class.
     *
     * @param class-string $className the name of the Document
     *
     * @return DocumentRepository<T>|GridFSRepository<T>|ViewRepository<T> the repository
     *
     * @template T of object
     */
    protected function getRepository(string $className): DocumentRepository|ViewRepository|GridFSRepository
    {
        return $this->getDocumentManager()->getRepository($className);
    }

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

    /**
     * Deletes the given document from the database.
     */
    protected function deleteDocument(string|object $class, array $criteria = []): void
    {
        if (is_object($class)) {
            $this->getDocumentManager()->remove($class);
            $this->getDocumentManager()->flush();

            return;
        }

        $repository = $this->getRepository($class);
        $object = $repository->findOneBy($criteria);

        $this->getDocumentManager()->remove($object);
        $this->getDocumentManager()->flush();
    }

    /**
     * Deletes all documents from the given collection.
     */
    protected function deleteAllDocuments(string $class): void
    {
        $repository = $this->getRepository($class);
        $objects = $repository->findAll();

        foreach ($objects as $object) {
            $this->getDocumentManager()->remove($object);
        }

        $this->getDocumentManager()->flush();
    }

    /**
     * Persists the given document to the database.
     */
    protected function persistDocument(object $document): void
    {
        $this->getDocumentManager()->persist($document);
        $this->getDocumentManager()->flush();
    }
}
