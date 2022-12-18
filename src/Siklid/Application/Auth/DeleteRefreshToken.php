<?php

declare(strict_types=1);

namespace App\Siklid\Application\Auth;

use App\Foundation\Action\AbstractAction;
use App\Siklid\Application\Auth\Request\DeleteRefreshTokenRequest;
use App\Siklid\Document\RefreshToken;
use Doctrine\ODM\MongoDB\DocumentManager;
use Gesdinet\JWTRefreshTokenBundle\Document\RefreshTokenRepository;

use function PHPUnit\Framework\assertNotNull;

final class DeleteRefreshToken extends AbstractAction
{
    private DeleteRefreshTokenRequest $request;
    private DocumentManager $dm;

    public function __construct(
        DeleteRefreshTokenRequest $request,
        DocumentManager $dm,
    ) {
        $this->request = $request;
        $this->dm = $dm;
    }

    public function execute(): bool
    {
        $refreshTokenRepository = $this->dm->getRepository(RefreshToken::class);

        assert($refreshTokenRepository instanceof RefreshTokenRepository);

        $refreshTokenVal = (string)$this->request->get('refreshToken');
        $refreshTokenObject = $refreshTokenRepository->findOneBy(['refreshToken' => $refreshTokenVal]);
        assertNotNull($refreshTokenObject);

        $this->dm->remove($refreshTokenObject);
        $this->dm->flush();

        return true;
    }
}
