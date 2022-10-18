<?php

declare(strict_types=1);

namespace App\Foundation\Actions;

use Doctrine\ODM\MongoDB\DocumentManager;

/**
 * Base action class.
 */
abstract class AbstractAction implements ActionInterface
{
    public function __construct(protected readonly DocumentManager $dm)
    {
    }

    /**
     * A wrapper for document manager persist method.
     */
    protected function persist(object $document): void
    {
        $this->dm->persist($document);
    }

    /**
     * A wrapper for document manager flush method.
     *
     * @psalm-suppress MixedArgumentTypeCoercion
     */
    protected function flush(array $options = []): void
    {
        $this->dm->flush($options);
    }
}
