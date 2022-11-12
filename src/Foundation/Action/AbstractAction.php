<?php

declare(strict_types=1);

namespace App\Foundation\Action;

use App\Foundation\Exception\ValidationException;
use App\Siklid\Document\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;

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
     * Validates the given form and throws a ValidationException if the form is not valid.
     *
     * @param FormInterface        $form    The form to validate
     * @param ValidatableInterface $request The validatable object that contains the data to validate
     */
    public function validate(FormInterface $form, ValidatableInterface $request): void
    {
        $form->submit($request->formInput());

        if (! $form->isValid()) {
            $validationException = new ValidationException();
            $validationException->setErrorIterator($form->getErrors(true));

            throw $validationException;
        }
    }
}
