<?php

declare(strict_types=1);

namespace App\Siklid\Form;

use App\Foundation\ValueObject\Email;
use App\Foundation\ValueObject\Username;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('email', EmailType::class)
            ->add('password', PasswordType::class)
            ->add('username', TextType::class);

        $builder->get('email')->addModelTransformer(
            new CallbackTransformer(
                static fn (?string $email) => is_null($email) ? null : Email::fromString($email),
                static fn (string $email) => Email::fromString($email),
            )
        );

        $builder->get('username')->addModelTransformer(
            new CallbackTransformer(
                static fn (?string $username) => is_null($username) ? null : Username::fromString($username),
                static fn (string $username) => Username::fromString($username),
            )
        );
    }
}
