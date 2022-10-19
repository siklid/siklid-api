<?php

declare(strict_types=1);

namespace App\Siklid\Auth;

use App\Foundation\Actions\AbstractAction;
use App\Siklid\Auth\Forms\UserType;
use App\Siklid\Auth\Requests\RegisterByEmailRequest;
use App\Siklid\Document\User;

class RegisterByEmail extends AbstractAction
{
    private readonly RegisterByEmailRequest $request;

    public function __construct(RegisterByEmailRequest $request)
    {
        $this->request = $request;
    }

    /**
     * Executes action.
     */
    public function execute(): User
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);
        $this->validate($form, $this->request);
        
        return $user;
    }
}
