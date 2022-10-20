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
        // @todo: fix validation
        $form->submit($request->formInput());

        if (!$form->isValid()) {
            $message = sprintf('Invalid `%s` form for `%s`', get_class($form), __FILE__);
            $validationException = new ValidationException($message);
            $validationException->setErrorIterator($form->getErrors(true));
            throw $validationException;
        }
    }
}
