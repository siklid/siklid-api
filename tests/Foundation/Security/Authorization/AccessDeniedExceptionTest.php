<?php

declare(strict_types=1);

namespace App\Tests\Foundation\Security\Authorization;

use App\Foundation\Security\Authorization\AccessDeniedException;
use App\Foundation\Security\Authorization\AuthorizableInterface;
use App\Tests\Concern\Util\WithJson;
use App\Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;

class AccessDeniedExceptionTest extends TestCase
{
    use WithJson;

    /**
     * @test
     *
     * @param string[]|string $attributes
     * @param string[]        $expected
     *
     * @dataProvider provide_attributes
     */
    public function set_and_get_attributes(array|string $attributes, array $expected): void
    {
        $sut = new AccessDeniedException();

        $sut->setAttributes($attributes);

        $this->assertSame($expected, $sut->getAttributes());
    }

    /** @test */
    public function set_and_get_subject(): void
    {
        $sut = new AccessDeniedException();
        $subject = $this->createMock(AuthorizableInterface::class);

        $sut->setSubject($subject);

        $this->assertSame($subject, $sut->getSubject());
    }

    /** @test */
    public function render(): void
    {
        $sut = new AccessDeniedException();
        $subject = $this->createMock(AuthorizableInterface::class);
        $subject->method('getKeyName')->willReturn('user');
        $subject->method('getHumanReadableName')->willReturn('User');
        $sut->setSubject($subject);
        $sut->setAttributes(['view', 'edit']);

        $response = $sut->render();

        $this->assertSame(Response::HTTP_FORBIDDEN, $response->getStatusCode());
        $this->assertEquals([
            'message' => 'Access Denied.',
            'errors' => [
                'user' => [
                    'You are not allowed to view this user.',
                    'You are not allowed to edit this user.',
                ],
            ],
        ], $this->json->jsonToArray((string)$response->getContent()));
    }

    public function provide_attributes(): array
    {
        return [
            'array' => [
                'attributes' => ['foo', 'bar'],
                'expected' => ['foo', 'bar'],
            ],
            'string' => [
                'attributes' => 'foo',
                'expected' => ['foo'],
            ],
        ];
    }
}
