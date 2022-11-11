<?php

declare(strict_types=1);

namespace App\Siklid\Command;

use App\Foundation\Service\Storage\StorageInterface;
use App\Foundation\Util\Yaml;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'siklid:postman',
    description: 'Generates a Postman collection from the API documentation'
)]
class PostmanCommand extends Console
{
    private StorageInterface $storage;
    private Yaml $yaml;

    public function __construct(StorageInterface $storage, Yaml $yaml)
    {
        parent::__construct();

        $this->storage = $storage;
        $this->yaml = $yaml;
    }

    public function handle(): int
    {
        $src = (string)$this->getParameter('postman.src');
        $dir = (string)$this->getParameter('postman.dir');
        $dist = (string)$this->getParameter('postman.dist');

        $srcYaml = $this->storage->read($src);
        $json = $this->yaml->toJson($srcYaml, $dir);

        $this->storage->write($dist, $json);

        $this->success('Postman collection generated at '.$dist);

        return self::SUCCESS;
    }
}
