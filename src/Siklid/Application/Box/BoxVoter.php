<?php

declare(strict_types=1);

namespace App\Siklid\Application\Box;

use App\Foundation\Security\Authorization\AbstractVoter;
use App\Siklid\Application\Contract\Entity\BoxInterface;
use App\Siklid\Application\Contract\Entity\UserInterface;

/**
 * @extends AbstractVoter<string, BoxInterface>
 */
final class BoxVoter extends AbstractVoter
{
    /**
     * @var class-string the subject that this voter supports
     */
    protected string $supportedClass = BoxInterface::class;

    /**
     * @var string[] the attributes that this voter supports
     */
    protected array $supportedAttributes = [
        self::CREATE,
        self::READ,
        self::UPDATE,
        self::DELETE,
    ];

    public function canCreate(): bool
    {
        return true;
    }

    public function canRead(BoxInterface $box, UserInterface $user): bool
    {
        if (! $box->isDeleted()) {
            return true;
        }

        return $box->getUser() === $user;
    }

    public function canUpdate(BoxInterface $box, UserInterface $user): bool
    {
        if (! $this->canRead($box, $user)) {
            return false;
        }

        return $box->getUser() === $user;
    }

    public function canDelete(BoxInterface $box, UserInterface $user): bool
    {
        return $this->canUpdate($box, $user);
    }
}
