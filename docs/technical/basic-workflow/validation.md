# Validation

Siklid uses the [Symfony validator](https://symfony.com/doc/current/components/validator.html) to validate the data and
the object states. This is done by injecting the `\App\Foundation\Validation\ValidatorInterface` into your action class,
and then calling the `abortUnlessValid` method.

If the validation fails, an exception is thrown, and the action is not executed. Don't worry, the exception is caught
by the framework, and the response is returned to the client.

```php
class CreateBox extends AbstractAction 
{
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }
    
    private function validateSomeData(array $data): void
    {
        $this->validator->stopUnlessValid($data, [
            'name' => new Assert\NotBlank(),
            'color' => new Assert\NotBlank(),
        ]);
    }
    
    private function validateSomeEntity(Box $box): void
    {
        $this->validator->abortUnlessValid($box);
        // Or you can pass the validation rules as the second argument.
        $this->validator->abortUnlessValid($box, [
            'name' => new Assert\NotBlank(),
            'color' => new Assert\NotBlank(),
        ]);
        // Or you can pass the validation groups as the third argument.
        $this->validator->abortUnlessValid($box, [], ['create']);
    }
}
```

## Validation Rules

All constraints are available in
the [Symfony documentation](https://symfony.com/doc/current/reference/constraints.html). We also have a
custom validation rules, which are listed below.

- [Exists](#exists)
- [Slug](#slug)

### Exists

Validates that the value exists in the database. It requires the `document` option to be set. And the `field` option
is optional, and it defaults to `id`. In the following example, the `user` field must be an existing user in the
database.

| Option   | Description              | Default |
|----------|--------------------------|---------|
| document | The document class name. |         |
| field    | The field name.          | id      |

```php
use Symfony\Component\Validator\Constraints as Assert;
use App\Foundation\Validation\Constraint as AppAssert;
class Box 
{
    // other properties are omitted for brevity.
    
    #[Assert\NotBlank]
    #[AppAssert\Exists(document: User::class)]
    private UserInterface $user;
}
```

### Slug

Validates that the value is a valid slug. No special options are required.

```php
use Symfony\Component\Validator\Constraints as Assert;
use App\Foundation\Validation\Constraint as AppAssert;

class Box 
{
    // other properties are omitted for brevity.
    
    #[Assert\NotBlank]
    #[AppAssert\Slug]
    private string $slug;
}
```

### Username

Validates that the value is a valid username. No special options are required.

```php
use Symfony\Component\Validator\Constraints as Assert;
use App\Foundation\Validation\Constraint as AppAssert;

class Box 
{
    // other properties are omitted for brevity.
    
    #[Assert\NotBlank]
    #[AppAssert\Username]
    private string $username;
}
```

### Unique document

Validates that the whole document is unique based on the given fields.

```php
use Symfony\Component\Validator\Constraints as Assert;
use App\Foundation\Validation\Constraint as AppAssert;

#[MongoDB\Document(collection: 'users')]
#[AppAssert\UniqueDocument(fields: ['email'])]
#[AppAssert\UniqueDocument(fields: ['username'])]
class User
{
    // other properties are omitted for brevity.
    
    #[Assert\NotBlank]
    #[Assert\Email]
    private string $email;
    
    #[Assert\NotBlank]
    #[AppAssert\Username]
    private string $username;
}
```
