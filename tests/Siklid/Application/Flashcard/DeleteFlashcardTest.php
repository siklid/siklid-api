<?php

declare(strict_types=1);

namespace App\Tests\Siklid\Application\Flashcard;

use App\Foundation\Http\Request;
use App\Foundation\Security\Authorization\AuthorizationCheckerInterface;
use App\Siklid\Application\Flashcard\DeleteFlashcard;
use App\Siklid\Document\Flashcard;
use App\Siklid\Document\User;
use App\Tests\TestCase;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class DeleteFlashcardTest extends TestCase
{
    /** @test */
    public function execute(): void
    {
        $request = $this->createMock(Request::class);
        $request->method('get')->willReturn('id');
        $documentManager = $this->createMock(DocumentManager::class);
        $repository = $this->createMock(DocumentRepository::class);
        $documentManager->method('getRepository')->willReturn($repository);
        $flashcard = $this->createMock(Flashcard::class);
        $repository->method('find')->willReturn($flashcard);
        $user = $this->createMock(User::class);
        $flashcard->method('getUser')->willReturn($user);
        $authChecker = $this->createMock(AuthorizationCheckerInterface::class);
        $sut = new DeleteFlashcard($request, $documentManager, $authChecker);

        $flashcard->expects($this->once())->method('delete');

        $sut->execute();
    }

    /** @test */
    public function delete_flashcard_with_invalid_id(): void
    {
        $request = $this->createMock(Request::class);
        $request->method('get')->willReturn('invalid_id');
        $documentManager = $this->createMock(DocumentManager::class);
        $repository = $this->createMock(DocumentRepository::class);
        $documentManager->method('getRepository')->willReturn($repository);
        $repository->method('find')->willReturn(null);
        $authChecker = $this->createMock(AuthorizationCheckerInterface::class);
        $sut = new DeleteFlashcard($request, $documentManager, $authChecker);

        $this->expectException(NotFoundHttpException::class);

        $sut->execute();
    }

    /** @test */
    public function only_flashcard_owner_can_delete_it(): void
    {
        $request = $this->createMock(Request::class);
        $request->method('get')->willReturn('id');
        $documentManager = $this->createMock(DocumentManager::class);
        $repository = $this->createMock(DocumentRepository::class);
        $documentManager->method('getRepository')->willReturn($repository);
        $flashcard = $this->createMock(Flashcard::class);
        $repository->method('find')->willReturn($flashcard);
        $user = $this->createMock(User::class);
        $flashcard->method('getUser')->willReturn($user);
        $authChecker = $this->createMock(AuthorizationCheckerInterface::class);
        $authChecker->method('denyAccessUnlessGranted')->willThrowException(new UnauthorizedHttpException(''));
        $sut = new DeleteFlashcard($request, $documentManager, $authChecker);

        $this->expectException(UnauthorizedHttpException::class);

        $sut->execute();
    }
}
