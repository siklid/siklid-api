<?php

declare(strict_types=1);

namespace App\Tests\Unit\Foundation\Util;

use App\Foundation\Util\Yaml;
use App\Tests\TestCase;
use Symfony\Component\Yaml\Parser;

/**
 * @psalm-suppress MissingConstructor
 */
class YamlTest extends TestCase
{
    private string $yaml;
    private string $yamlLocation;
    private Yaml $sut;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->yaml = file_get_contents(__DIR__.'/../../../fixtures/yaml/collection.yaml');
        $this->yamlLocation = __DIR__.'/../../../fixtures/yaml';
        $this->sut = new Yaml(new Parser(), $this->json);
    }

    /**
     * @test
     */
    public function to_json(): void
    {
        $json = $this->sut->toJson($this->yaml, $this->yamlLocation);

        $this->assertJsonStringEqualsJsonString(
            $this->json->arrayToJson([
                'info' => [
                    'name' => 'postman',
                    'description' => "Multiple \nlines \nof \ntext",
                ],
                'item' => [
                    [
                        'name' => 'first_item',
                        'request' => [
                            'url' => '/auth/register/email',
                            'method' => 'POST',
                        ],
                    ],
                    [
                        'name' => 'second_item',
                        'request' => [
                            'url' => '/auth/login/email',
                            'method' => 'POST',
                            'foo' => 'bar',
                        ],
                        'response' => [
                            'foo' => 'bar',
                            'baz' => 'qux',
                        ],
                    ],
                ],
            ]),
            $json
        );
    }

    /**
     * @test
     */
    public function render(): void
    {
        $yaml = $this->sut->render($this->yaml, $this->yamlLocation);

        $expected = <<<YAML
info:
  name: postman
  description: |-
    Multiple 
    lines 
    of 
    text
item:
  - name: first_item
    request:
      url: /auth/register/email
      method: POST
  - name: second_item
    request:
      url: '/auth/login/email'
      method: POST
      foo: bar
    response:
      foo: bar
      baz: qux
YAML;

        $this->assertSame($expected, $yaml);
    }
}
