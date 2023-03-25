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
a specific `Flashcard` entity, you should create a `FlashcardVoter` class in the `App\Siklid\Application\Flashcard`
namespace.

Then extend the `\App\Foundation\Security\Authorization\AbstractVoter` class. Then, you should set two properties:

- `$supportedClass` - the class that the voter supports (in our example, it's `FlashcardInterface::class`).
- `$supportedAttributes` - the attributes that the voter supports. It should be an array of strings.

Finally, you should implement a method for each attribute that you want to support. The method name should start with
`can` and end with the attribute name. For example, if you want to support the `view` attribute, you should create a
`canView` method. The method should return a boolean value.

You can find an example of a voter in
the `App\Siklid\Application\Flashcard\FlashcardVoter` [class](../../../src/Siklid/Application/Flashcard/FlashcardVoter.php).
