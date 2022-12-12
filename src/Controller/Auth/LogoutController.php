<?php

declare(strict_types=1);

namespace App\Controller\Auth;

use App\Foundation\Http\ApiController;
use App\Foundation\Redis\Contract\SetInterface;
use App\Siklid\Application\Auth\Request\LogoutRequest;
use App\Siklid\Document\RefreshToken;
use \Gesdinet\JWTRefreshTokenBundle\Document\RefreshTokenRepository;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class LogoutController extends ApiController
{
    private DocumentManager $documentManager;
//    private RefreshTokenRepository $refreshTokenRepository;

    public function __construct(
        DocumentManager $documentManager,
//        RefreshTokenRepository $refreshTokenRepository
    ) {
        $this->documentManager = $documentManager;
//        $this->refreshTokenRepository = $refreshTokenRepository;
    }

    #[Route('/auth/logout', name: 'auth_logout', methods: ['POST'])]
    public function logout(LogoutRequest $request, SetInterface $set): Response
    {
        $refreshTokenRepository = $this->documentManager->getRepository(RefreshToken::class);
        assert($refreshTokenRepository instanceof RefreshTokenRepository);
        $refreshToken = $refreshTokenRepository->findOneBy(['refreshToken' => $request->all()['refreshToken']]);

        if (!$refreshToken instanceof RefreshToken) {
            throw new NotFoundHttpException(
                sprintf('RefreshToken with value [%s] cannot be found.', $refreshToken)
            );
        }

        $this->documentManager->remove($refreshToken);
        $this->documentManager->flush();

        $tokenWithBearer = $request->request()->headers->get('Authorization');
        $userId = $this->getUser()->getId();

        $setKey = 'user.'.$userId.'.accessToken';
        $set->add($setKey, $tokenWithBearer);
        $set->setTtl($setKey, time()+(60*60));

        $data = [
            'message' => 'You have been logged out.',
        ];

        return $this->json($data, Response::HTTP_OK);
    }
}
