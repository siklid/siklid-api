<?php

declare(strict_types=1);

namespace App\Siklid\Application\Auth;

use App\Foundation\Action\AbstractAction;
use App\Foundation\Http\Request;
use App\Foundation\Redis\Contract\SetInterface;
use App\Siklid\Application\Contract\Entity\UserInterface;

final class InvalidAccessToken extends AbstractAction
{
    private SetInterface $set;
    private Request $request;

    public function __construct(Request $request, SetInterface $set)
    {
        $this->set = $set;
        $this->request = $request;
    }

    public function execute(): bool
    {
        $tokenWithBearer = (string)$this->request->request()->headers->get('Authorization');
        $user = $this->getUser();
        assert($user instanceof UserInterface);

        $userId = $user->getId();

        $setKey = 'user.'.$userId.'.accessToken';
        $this->set->add($setKey, $tokenWithBearer);
        $this->set->setTtl($setKey, time() + (60 * 60));

        return true;
    }
}
