<?php

namespace App\Foundation\Exceptions;

use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormErrorIterator;

class ValidationException extends SiklidException
{
    protected ?FormErrorIterator $errorIterator = null;

    /**
     * @param FormErrorIterator<FormError> $errorIterator
     *
     * @return void
     */
    public function setErrorIterator(FormErrorIterator $errorIterator): void
    {
        $this->errorIterator = $errorIterator;
    }
}
