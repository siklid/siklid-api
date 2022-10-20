<?php

declare(strict_types=1);

namespace App\Foundation\Actions;

use App\Foundation\Exceptions\ValidationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;

/**
 * Base action class.
 */
abstract class AbstractAction extends AbstractController implements ActionInterface
{
    protected function validate(FormInterface $form, ValidatableInterface $request): void
    {
        $form->submit($request->formInput());

        if (!$form->isValid()) {
            $validationException = new ValidationException();
            $validationException->setErrorIterator($form->getErrors(true));

            throw $validationException;
        }
    }
}
