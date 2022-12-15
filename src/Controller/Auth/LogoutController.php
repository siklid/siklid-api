<?php

declare(strict_types=1);

namespace App\Controller\Auth;

use App\Foundation\Http\ApiController;
use App\Foundation\Redis\Contract\SetInterface;
use App\Siklid\Application\Auth\Request\LogoutRequest;
use App\Siklid\Document\RefreshToken;
use App\Siklid\Document\User;
use Doctrine\ODM\MongoDB\DocumentManager;
use Gesdinet\JWTRefreshTokenBundle\Document\RefreshTokenRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class LogoutController extends ApiController
{
    private DocumentManager $documentManager;

    public function __construct(DocumentManager $documentManager)
    {
        $this->documentManager = $documentManager;
    }

    #[Route('/auth/logout', name: 'auth_logout', methods: ['POST'])]
    public function logout(LogoutRequest $request, SetInterface $set): Response
    {
        $refreshTokenRepository = $this->documentManager->getRepository(RefreshToken::class);

        assert($refreshTokenRepository instanceof RefreshTokenRepository);

        $refreshTokenVal = (string)$request->all()['refreshToken'];
        $refreshTokenObject = $refreshTokenRepository->findOneBy(['refreshToken' => $refreshTokenVal]);

        if (! $refreshTokenObject instanceof RefreshToken) {
            throw new NotFoundHttpException(sprintf('RefreshToken with value [%s] cannot be found.', $refreshTokenVal));
        }

        $this->documentManager->remove($refreshTokenObject);
        $this->documentManager->flush();

        $tokenWithBearer = $request->request()->headers->get('Authorization');

        $user = $this->getUser();
        assert($user instanceof User);

        $userId = $user->getId();

        $setKey = 'user.'.$userId.'.accessToken';
        $set->add($setKey, $tokenWithBearer);
        $set->setTtl($setKey, time() + (60 * 60));

        $data = [
            'message' => 'You have been logged out.',
        ];

        return $this->json($data, Response::HTTP_OK);
    }
}
