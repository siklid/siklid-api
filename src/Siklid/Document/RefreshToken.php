<?php

declare(strict_types=1);

namespace App\Siklid\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Gesdinet\JWTRefreshTokenBundle\Document\RefreshTokenRepository;
use Gesdinet\JWTRefreshTokenBundle\Entity\RefreshToken as BaseRefreshToken;

#[ODM\Document(collection: 'refresh_tokens', repositoryClass: RefreshTokenRepository::class)]
class RefreshToken extends BaseRefreshToken
{
    #[ODM\Id]
    protected $id;

    #[ODM\Field(type: 'string')]
    protected $refreshToken;

    #[ODM\Field(type: 'string')]
    protected $username;

    #[ODM\Field(type: 'date', nullable: true)]
    protected $valid;
}
