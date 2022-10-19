<?php

declare(strict_types=1);

namespace App\Siklid\Auth;

use App\Foundation\Actions\AbstractAction;
use App\Foundation\Exceptions\ValidationException;
use App\Foundation\Http\Request;
use App\Siklid\Auth\Forms\RegisterByEmailForm;
use App\Siklid\Document\User;

class RegisterByEmail extends AbstractAction
{
    public function __construct(private readonly Request $request)
    {
    }

    /**
     * Executes action.
     */
    public function execute(): User
    {
        $user = new User();

        $form = $this->createForm(RegisterByEmailForm::class, $user);
        $form->submit($this->request->formInput());

        if (! $form->isValid()) {
            $message = sprintf('Invalid `%s` form for `%s`', get_class($form), __FILE__);
            $validationException = new ValidationException($message);
            $validationException->setErrorIterator($form->getErrors(true));

            throw $validationException;
        }
        
        return $user;
    }
}
