# Authorization

## Introduction

You don't need to learn much more
than [how authorization works in Symfony](https://symfony.com/doc/current/security.html#access-control-authorization) to
understand how it works in Siklid. Below you will find explanation of the parts that are developed differently in
Siklid.

## How to use voters to check user permissions

Voters in Siklid are used to check if a user has access to a specific resource. It works the same way as in Symfony, but
there are some differences.

### How to create a voter

The Voter class must be created in the Subdomain namespace. For example, if you want to check if a user has access to
a specific `Box` entity, you should create a `BoxVoter` class in the `App\Siklid\Application\Box` namespace.

Then extend the `\App\Foundation\Security\Authorization\AbstractVoter` class. Then, you should set two properties:

- `$supportedClass` - the class that the voter supports (in our example, it's `Box::class`).
- `$supportedAttributes` - the attributes that the voter supports. It should be an array of strings.

Finally, you should implement a method for each attribute that you want to support. The method name should start with
`can` and end with the attribute name. For example, if you want to support the `view` attribute, you should create a
`canView` method. The method should return a boolean value.

```php
declare(strict_types=1);

namespace App\Siklid\Application\Box;

use App\Foundation\Security\Authorization\AbstractVoter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use App\Siklid\Application\Contract\Entity\UserInterface;

class BoxVoter extends AbstractVoter
{
    protected $supportedClass = Box::class;

    protected $supportedAttributes = [
        'view',
        'edit',
        'delete',
    ];

    protected function canView(Box $box, UserInterface $user): bool
    {
        // ...
    }

    protected function canEdit(Box $box, UserInterface $user): bool
    {
        // ...
    }
    
    protected function canDelete(Box $box, UserInterface $user): bool
    {
        // ...
    }
}
```
