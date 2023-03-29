<?php

declare(strict_types=1);

namespace App\Siklid\Application\Box;

use App\Foundation\Exception\LogicException;
use App\Siklid\Application\Contract\Entity\BoxInterface;
use App\Siklid\Application\Contract\Entity\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class BoxVoter extends Voter
{
    public const VIEW = 'view';
    public const DELETE = 'delete';
    public const UPDATE = 'update';

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (! in_array($attribute, [self::VIEW, self::DELETE, self::UPDATE])) {
            return false;
        }

        if (! $subject instanceof BoxInterface) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (! $user instanceof UserInterface) {
            return false;
        }

        /** @var BoxInterface $box */
        $box = $subject;

        return match ($attribute) {
            self::VIEW => $this->canView($box, $user),
            self::DELETE => $this->canDelete($box, $user),
            self::UPDATE => $this->canUpdate($box, $user),
            default => throw new LogicException('This code should not be reached!'),
        };
    }

    public function canView(BoxInterface $box, UserInterface $user): bool
    {
        if (! $box->isDeleted()) {
            return true;
        }

        return $box->getUser() === $user;
    }

    public function canUpdate(BoxInterface $box, UserInterface $user): bool
    {
        if (! $this->canView($box, $user)) {
            return false;
        }

        return $box->getUser() === $user;
    }

    public function canDelete(BoxInterface $box, UserInterface $user): bool
    {
        return $this->canUpdate($box, $user);
    }
}
