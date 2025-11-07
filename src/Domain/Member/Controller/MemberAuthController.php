<?php

namespace App\Domain\Member\Controller;

use App\Domain\User\Entity\User;
use App\Domain\Geo\Entity\GeoRegion;
use App\Domain\Member\Entity\Member;
use App\Domain\Member\Entity\MemberActivationCode;
use App\Domain\Member\Entity\MemberProfile;
use App\Domain\User\Repository\UserRepository;
use App\Domain\Member\Repository\MemberRepository;
use App\Domain\Member\Repository\MemberAccessLogRepository;
use App\Domain\Member\Repository\MemberActivationCodeRepository;
use App\Domain\Member\Repository\MemberProfileRepository;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Domain\Member\Mail\UserRegisterMail;
use Symfony\Component\Messenger\MessageBusInterface;
use App\Domain\Member\Message\UserRegisterMessage;

#[Route('/api/v1/auth')]
class MemberAuthController extends AbstractController
{
    private int $jwtTime = 60;

    public function __construct(
        private EntityManagerInterface $em,
        private JWTTokenManagerInterface $jwtManager,
        private UserPasswordHasherInterface $passwordHasher,
        private ValidatorInterface $validator,
        private UserRepository $userRepo,
        private MemberRepository $memberRepo,
        private MemberProfileRepository $profileRepo,
        private MemberActivationCodeRepository $activationRepo,
        private MemberAccessLogRepository $accessLogRepo,
        private MessageBusInterface $messageBus,
    ) {}

    #[Route('/register', name: 'api_member_register', methods: ['POST'])]
    public function register(Request $request, UserRegisterMail $registrationMail): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $errors = [];
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email is required and must be valid.';
        }
        if (empty($data['password']) || strlen($data['password']) < 6) {
            $errors['password'] = 'Password is required and must be at least 6 characters.';
        }
        if (empty($data['nickname'])) {
            $errors['nickname'] = 'Nickname is required.';
        }

        if ($errors) {
            $firstField = array_key_first($errors);
            return new JsonResponse(['message' => $errors[$firstField], 'error' => $firstField], JsonResponse::HTTP_NOT_ACCEPTABLE);
        }

        if ($this->userRepo->findOneByEmail($data['email'])) {
            return new JsonResponse(['message' => 'Email already registered.', 'error' => 'email'], JsonResponse::HTTP_NOT_ACCEPTABLE);
        }

        $user = new User();
        $user->setRole('ROLE_MEMBER');
        $user->setEmail($data['email']);
        $user->setPassword($this->passwordHasher->hashPassword($user, $data['password']));

        $this->em->persist($user);
        $this->em->flush();

        $region = $this->em->getRepository(GeoRegion::class)->find($data['region_id'] ?? 1);
        if (! $region) {
            return $this->json(
                [
                    'message' => 'Region not found.',
                    'error' => 'region_not_found'
                ],
                JsonResponse::HTTP_NOT_FOUND
            );
        }

        $member = new Member();
        $member->setUser($user);
        $member->setRegion($region);
        $this->em->persist($member);
        $this->em->flush();

        $requiresActivation = (bool) ($_ENV['LOGIN_ACTIVATION_CODE'] ?? false);
        $activation = new MemberActivationCode();
        $activation->setUser($user);
        $activation->setIsActive(!$requiresActivation);
        $activation->generateCode();
        $this->em->persist($activation);

        $profile = new MemberProfile();
        $profile->setUser($user);
        $profile->setNickname($data['nickname']);
        $this->em->persist($profile);

        $this->em->flush();

        $payload = [
            'uid' => $member->getUid(),
            'email' => $user->getEmail(),
            'nickname' => $profile->getNickname(),
            'activation_code' => $activation->getCode(),
        ];

        $envMailSend  = (bool) ($_ENV['MAIL_SEND'] ?? false);
        $envQueueSend = (bool) ($_ENV['QUEUE_SEND'] ?? false);
        if ($envMailSend) {
            if ($envQueueSend) {
                $this->messageBus->dispatch(new UserRegisterMessage($payload));
            } else {
                $registrationMail->send($payload);
            }
        }

        return new JsonResponse($payload, JsonResponse::HTTP_CREATED);
    }

    #[Route('/activation', name: 'api_member_activation', methods: ['POST'])]
    public function activation(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $errors = [];
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email is required and must be valid.';
        }
        if (empty($data['code'])) {
            $errors['code'] = 'Activation code is required.';
        }
        if ($errors) {
            $firstField = array_key_first($errors);
            return new JsonResponse(['message' => $errors[$firstField], 'error' => $firstField], JsonResponse::HTTP_NOT_ACCEPTABLE);
        }

        $user = $this->userRepo->findOneByEmail($data['email']);
        if (! $user) {
            return new JsonResponse(['message' => 'User not found.', 'error' => 'email'], JsonResponse::HTTP_NOT_ACCEPTABLE);
        }

        $activation = $this->activationRepo->findOneBy(['user' => $user, 'code' => $data['code']]);
        if (! $activation) {
            return new JsonResponse(['message' => 'Invalid activation code.', 'error' => 'activation_code'], JsonResponse::HTTP_NOT_ACCEPTABLE);
        }

        $activation->setIsActive(true);
        $this->em->flush();

        return new JsonResponse([
            'email' => $user->getEmail(),
            'status' => 'Account activation has been activated.',
        ], JsonResponse::HTTP_ACCEPTED);
    }

    #[Route('/login', name: 'api_member_login', methods: ['POST'])]
    public function login(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $errors = [];
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email is required and must be valid.';
        }
        if (empty($data['password'])) {
            $errors['password'] = 'Password is required.';
        }
        if ($errors) {
            $firstField = array_key_first($errors);
            return new JsonResponse(['message' => $errors[$firstField], 'error' => $firstField], JsonResponse::HTTP_UNAUTHORIZED);
        }

        /* @var \App\Domain\User\Entity\User $user */
        $user = $this->userRepo->findOneByEmail($data['email']);
        if (
            ! $user ||
            ! $this->passwordHasher->isPasswordValid($user, $data['password']) ||
            ! in_array('ROLE_MEMBER', $user->getRoles(), true)
        ) {
            return new JsonResponse(['message' => 'Invalid credentials.'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        // From now on, output is managed by ./src/Security/CustomAuthenticationSuccessHandler.php
        return new JsonResponse([], JsonResponse::HTTP_OK);
    }

    #[Route('/refresh', name: 'api_member_refresh', methods: ['POST'])]
    public function refresh(Request $request): JsonResponse
    {
        $token = str_replace('Bearer ', '', $request->headers->get('Authorization', ''));
        if (! $token) {
            return new JsonResponse(['message' => 'Token not provided.', 'error' => 'token_not_provided'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $accessToken = $this->accessLogRepo->findOneBy(['token' => $token]);
        if (! $accessToken) {
            return new JsonResponse(['message' => 'Token not registered.', 'error' => 'token_not_found'], JsonResponse::HTTP_NOT_FOUND);
        }
        if ($accessToken->getIsTerminated()) {
            return new JsonResponse(['message' => 'Token cannot be refreshed.', 'error' => 'token_terminated'], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        // LexikJWT does not provide a refresh mechanism by default, this implementation re-issues the token
        $user = $accessToken->getUser();
        $legacyAccessTokenCeasedAt = $accessToken->getExpiresAt();
        $refreshedToken = $this->jwtManager->create($user);

        $accessToken->setExpiresAt((new \DateTime())->modify("+{$this->jwtTime} minutes"));
        $accessToken->setRefreshCount($accessToken->getRefreshCount() + 1);
        $accessToken->setToken($refreshedToken);
        $this->em->flush();

        return new JsonResponse([
            'token' => $accessToken->getToken(),
            'expires_in' => $this->jwtTime * 60,
            'token_expired' => $token,
            'token_expired_ceased' => $legacyAccessTokenCeasedAt,
        ], JsonResponse::HTTP_ACCEPTED);

        return new JsonResponse(['user' => $user->getId()], JsonResponse::HTTP_OK);
    }

    #[Route('/logout', name: 'api_member_logout', methods: ['POST'])]
    public function logout(Request $request): JsonResponse
    {
        $token = str_replace('Bearer ', '', $request->headers->get('Authorization', ''));
        if (!$token) {
            return new JsonResponse(['message' => 'Token not provided.', 'error' => 'token_not_provided'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $accessToken = $this->accessLogRepo->findOneBy(['token' => $token]);
        if (!$accessToken) {
            return new JsonResponse(['message' => 'Token not registered.', 'error' => 'token_not_found'], JsonResponse::HTTP_NOT_FOUND);
        }
        if ($accessToken->getIsTerminated()) {
            return new JsonResponse(['message' => 'Token is already terminated.', 'error' => 'token_terminated'], JsonResponse::HTTP_NOT_MODIFIED);
        }

        $accessToken->setIsTerminated(true);
        $this->em->flush();

        // LexikJWT does not have a built-in invalidate, but you can blacklist tokens if enabled

        return new JsonResponse(['token_expired' => $token], JsonResponse::HTTP_ACCEPTED);
    }

    #[Route('/whoami', name: 'api_member_whoami', methods: ['GET'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function whoami(#[CurrentUser] User $user): JsonResponse
    {
        $member = $this->memberRepo->findOneBy(['user' => $user]);
        $profile = $this->profileRepo->findOneBy(['user' => $user]);

        return new JsonResponse([
            'email' => $user->getEmail(),
            'uid' => $member ? $member->getUid() : null,
            'nickname' => $profile ? $profile->getNickname() : null,
            'avatar' => $profile ? $profile->getAvatar() : null,
        ], JsonResponse::HTTP_OK);
    }
}
