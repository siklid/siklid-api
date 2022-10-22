<?php

declare(strict_types=1);

namespace App\Siklid\Command;

use App\Siklid\Document\User;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

/**
 * Class SetupCommand.
 *
 * This class is used to set up the application
 *
 * @todo: add test for the setup command
 */
#[AsCommand(
    name: 'siklid:setup',
    description: 'Setup the application'
)]
class SetupCommand extends Console
{
    private DocumentManager $dm;

    private PasswordHasherInterface $hasher;

    public function __construct(DocumentManager $dm, PasswordHasherFactoryInterface $hasherFactory)
    {
        $this->dm = $dm;
        $this->hasher = $hasherFactory->getPasswordHasher(User::class);

        parent::__construct();
    }

    /**
     * Executes the current command.
     */
    public function handle(): int
    {
        $this->info('Setup the application...');

        $this->createAdminUser();

        return self::SUCCESS;
    }

    private function createAdminUser(): void
    {
        $usersRepository = $this->dm->getRepository(User::class);
        $exists = $usersRepository->findOneBy(['username' => 'admin']);

        if ($exists) {
            $this->warning('- Admin user already exists.');

            return;
        }

        $user = new User();

        $user->setUsername('admin');
        // @todo: config admin password or ask for it
        $user->setPassword($this->hasher->hash('admin'));
        $user->setEmail('admin@siklid.io');

        $this->dm->persist($user);
        $this->dm->flush();

        $this->success('- Admin user created.');
    }
}
