<?php

declare(strict_types=1);

namespace App\Tests\Foundation\Security\Token;

use App\Foundation\Action\ConfigInterface;
use App\Foundation\Security\Token\RefreshTokenManager;
use App\Foundation\Security\Token\RefreshTokenManagerInterface;
use App\Tests\Concern\Util\WithFaker;
use App\Tests\TestCase;
use Gesdinet\JWTRefreshTokenBundle\Generator\RefreshTokenGeneratorInterface as GesdinetGenerator;
use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenInterface;
use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenManagerInterface as GesdinetManager;
use Symfony\Component\Security\Core\User\UserInterface;

class RefreshTokenManagerTest extends TestCase
{
    use WithFaker;

    /**
     * @test
     */
    public function create_for_user(): void
    {
        $generator = $this->createMock(GesdinetGenerator::class);
        $manager = $this->createMock(GesdinetManager::class);
        $config = $this->createMock(ConfigInterface::class);
        $ttl = $this->faker->numberBetween(1, 3600);
        $config->expects($this->once())
            ->method('get')
            ->with('@gesdinet_jwt_refresh_token.ttl')
            ->willReturn($ttl);

        $sut = new RefreshTokenManager($generator, $manager, $config);
        $user = $this->createMock(UserInterface::class);

        $refreshToken = $this->createMock(RefreshTokenInterface::class);
        $generator->expects($this->once())
            ->method('createForUserWithTtl')
            ->with($user, $ttl)
            ->willReturn($refreshToken);

        $manager->expects($this->once())->method('save')->with($refreshToken);

        $actual = $sut->createForUser($user, RefreshTokenManagerInterface::CONFIGURED_TTL);

        $this->assertSame($refreshToken, $actual);
    }

    /**
     * @test
     */
    public function create_for_user_uses_passed_ttl_if_gt_zero(): void
    {
        $generator = $this->createMock(GesdinetGenerator::class);
        $manager = $this->createMock(GesdinetManager::class);
        $config = $this->createMock(ConfigInterface::class);
        $ttl = $this->faker->numberBetween(1, 3600);
        $config->expects($this->never())->method('get');

        $sut = new RefreshTokenManager($generator, $manager, $config);
        $user = $this->createMock(UserInterface::class);

        $refreshToken = $this->createMock(RefreshTokenInterface::class);
        $generator->expects($this->once())
            ->method('createForUserWithTtl')
            ->with($user, $ttl)
            ->willReturn($refreshToken);

        $manager->expects($this->once())->method('save')->with($refreshToken);

        $actual = $sut->createForUser($user, $ttl);

        $this->assertSame($refreshToken, $actual);
    }

    /**
     * @test
     */
    public function revoke(): void
    {
        $generator = $this->createMock(GesdinetGenerator::class);
        $manager = $this->createMock(GesdinetManager::class);
        $config = $this->createMock(ConfigInterface::class);

        $sut = new RefreshTokenManager($generator, $manager, $config);
        $refreshToken = $this->createMock(RefreshTokenInterface::class);

        $manager->expects($this->once())->method('delete')->with($refreshToken);
        $manager->expects($this->once())->method('save')->with($refreshToken);

        $sut->revoke($refreshToken);
    }
}
