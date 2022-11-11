<?php

declare(strict_types=1);

namespace App\Siklid\Command;

use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class Console.
 * All commands should extend this class.
 *
 * @psalm-suppress MixedReturnStatement
 * @psalm-suppress UndefinedInterfaceMethod
 * @psalm-suppress MixedInferredReturnType
 */
abstract class Console extends Command
{
    /** @psalm-suppress PropertyNotSetInConstructor */
    protected InputInterface $input;

    /** @psalm-suppress PropertyNotSetInConstructor */
    protected OutputInterface $output;

    /**
     * Adds setups to the command instance
     * Executes the current command.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->input = $input;
        $this->output = $output;

        return $this->handle();
    }

    /**
     * Executes the current command.
     */
    abstract public function handle(): int;

    protected function info(string $message): void
    {
        $this->output->writeln(sprintf('<info>%s</info>', $message));
    }

    protected function error(string $message): void
    {
        $this->output->writeln(sprintf('<error>%s</error>', $message));
    }

    protected function warning(string $message): void
    {
        $this->output->writeln(sprintf('<comment>%s</comment>', $message));
    }

    protected function question(string $message): void
    {
        $this->output->writeln(sprintf('<question>%s</question>', $message));
    }

    protected function success(string $message): void
    {
        $this->output->writeln(sprintf('<info>%s</info>', $message));
    }

    protected function debug(string $message): void
    {
        $this->output->writeln(sprintf('<debug>%s</debug>', $message));
    }

    protected function clickableLink(string $link, ?string $message = null): void
    {
        $message = $message ?? $link;

        $this->output->writeln(sprintf('<href=%s>%s</>', $link, $message));
    }

    protected function separator(string $tag = 'info'): void
    {
        $this->$tag(str_repeat('-', 80));
    }

    protected function getKernel(): KernelInterface
    {
        /** @var Application|null $app */
        $app = $this->getApplication();

        if (null === $app) {
            throw new RuntimeException('Application is not set');
        }

        return $app->getKernel();
    }

    /**
     * @psalm-suppress MixedAssignment
     * @psalm-suppress MixedArrayAccess
     * @psalm-suppress PossiblyNullArrayAccess
     * @psalm-suppress PossiblyNullReference
     * @psalm-suppress PossiblyUndefinedMethod
     */
    protected function getParameter(string $name): mixed
    {
        $nameParts = explode('.', $name);
        $accessKey = array_shift($nameParts);
        $data = $this->getKernel()->getContainer()->getParameter($accessKey);

        foreach ($nameParts as $namePart) {
            $data = $data[$namePart];
        }

        return $data;
    }
}
