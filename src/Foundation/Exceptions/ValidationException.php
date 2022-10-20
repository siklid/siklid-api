<?php

declare(strict_types=1);

namespace App\Foundation\Exceptions;

use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormErrorIterator;

class ValidationException extends SiklidException
{
    protected ?FormErrorIterator $errorIterator = null;

    /**
     * @param FormErrorIterator<FormError> $errorIterator
     */
    public function setErrorIterator(FormErrorIterator $errorIterator): void
    {
        $this->errorIterator = $errorIterator;
    }
}
