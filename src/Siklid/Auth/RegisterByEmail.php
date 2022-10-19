<?php

declare(strict_types=1);

namespace App\Siklid\Auth;

use App\Foundation\Actions\AbstractAction;
use App\Siklid\Auth\Forms\UserType;
use App\Siklid\Auth\Requests\RegisterByEmailRequest as Request;
use App\Siklid\Document\User;
use Doctrine\ODM\MongoDB\DocumentManager;

class RegisterByEmail extends AbstractAction
{
    private readonly Request $request;

    private readonly DocumentManager $dm;

    public function __construct(Request $request, DocumentManager $dm)
    {
        $this->request = $request;
        $this->dm = $dm;
    }

    /**
     * Executes action.
     */
    public function execute(): User
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);
        $this->validate($form, $this->request);

        $this->dm->persist($user);
        $this->dm->flush();

        return $user;
    }
}
