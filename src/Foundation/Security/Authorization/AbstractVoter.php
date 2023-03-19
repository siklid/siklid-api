<?php

declare(strict_types=1);

namespace App\Foundation\Security\Authorization;

use App\Foundation\Exception\InvalidArgumentException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * Abstract Voter
 * All Voters should extend this class. It provides some basic functionality
 * and makes sure that the voter source code is descriptive to the developer.
 */
abstract class AbstractVoter extends Voter
{
    /**
     * @var string[] the attributes that this voter supports
     */
    protected array $supportedAttributes = [];

    /**
     * @var class-string|null the subject that this voter supports
     */
    protected ?string $supportedClass = null;

    public function __construct()
    {
        if (is_null($this->supportedClass)) {
            throw new InvalidArgumentException('The subject must be set.');
        }
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $method = 'can'.ucfirst($attribute);
        $user = $token->getUser();
        if (is_null($user)) {
            return false;
        }

        $vote = $this->$method($subject, $user);
        assert(is_bool($vote));

        return $vote;
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (! in_array($attribute, $this->supportedAttributes)) {
            return false;
        }

        if (! is_null($this->supportedClass) && ! is_a($subject, $this->supportedClass)) {
            return false;
        }

        return true;
    }
}
