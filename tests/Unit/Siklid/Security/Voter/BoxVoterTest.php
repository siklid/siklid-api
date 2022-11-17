<?php

declare(strict_types=1);

namespace App\Tests\Unit\Siklid\Security\Voter;

use App\Siklid\Document\Box;
use App\Siklid\Document\User;
use App\Siklid\Security\Voter\BoxVoter;
use App\Tests\TestCase;

/**
 * @psalm-suppress MissingConstructor
 */
class BoxVoterTest extends TestCase
{
    /**
     * @test
     */
    public function box_is_public(): void
    {
        $sut = new BoxVoter();
        $visitor = new User();
        $box = new Box();

        $actual = $sut->canView($box, $visitor);

        self::assertTrue($actual);
    }

    /**
     * @test
     */
    public function deleted_box_is_not_public(): void
    {
        $sut = new BoxVoter();
        $visitor = new User();
        $box = new Box();
        $box->setUser(new User());
        $box->delete();

        $actual = $sut->canView($box, $visitor);

        self::assertFalse($actual);
    }

    /**
     * @test
     */
    public function user_can_view_his_deleted_box(): void
    {
        $sut = new BoxVoter();
        $user = new User();
        $box = new Box();
        $box->setUser($user);
        $box->delete();

        $actual = $sut->canView($box, $user);

        self::assertTrue($actual);
    }

    /**
     * @test
     */
    public function box_visitor_can_not_update_it(): void
    {
        $sut = new BoxVoter();
        $visitor = new User();
        $box = new Box();
        $box->setUser(new User());

        $actual = $sut->canUpdate($box, $visitor);

        self::assertFalse($actual);
    }

    /**
     * @test
     */
    public function box_owner_can_update_her_box(): void
    {
        $sut = new BoxVoter();
        $user = new User();
        $box = new Box();
        $box->setUser($user);

        $actual = $sut->canUpdate($box, $user);

        self::assertTrue($actual);
    }

    /**
     * @test
     */
    public function box_visitor_can_not_delete_it(): void
    {
        $sut = new BoxVoter();
        $visitor = new User();
        $box = new Box();
        $box->setUser(new User());

        $actual = $sut->canDelete($box, $visitor);

        self::assertFalse($actual);
    }

    /**
     * @test
     */
    public function box_owner_can_delete_her_box(): void
    {
        $sut = new BoxVoter();
        $user = new User();
        $box = new Box();
        $box->setUser($user);

        $actual = $sut->canDelete($box, $user);

        self::assertTrue($actual);
    }
}
