<?php

declare(strict_types=1);

namespace App\Siklid\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

#[MongoDB\Document(collection: 'oauth_clients')]
class OAuthClient
{
    #[MongoDB\Id]
    private string $id;
}
