<?php

declare(strict_types=1);

namespace App\Siklid\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('email')
            ->add('password')
            ->add('username');
    }
}
