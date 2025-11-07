<?php

namespace App\Domain\Member\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class MemberAccountController extends AbstractController
{
    #[Route('/api/v1/account/sections', name: 'member_account_sections', methods: ['GET'])]
    public function listSections(Request $request): JsonResponse
    {
        $response = ['test' => true];

        return $this->json($response, JsonResponse::HTTP_OK);
    }

    #[Route('/api/v1/account/profile', name: 'member_account_profile_read', methods: ['GET'])]
    public function readProfile(Request $request): JsonResponse
    {
        $response = ['test' => true];

        return $this->json($response, JsonResponse::HTTP_OK);
    }

    #[Route('/api/v1/account/profile', name: 'member_account_profile_update', methods: ['PUT', 'PATCH'])]
    public function updateProfile(Request $request): JsonResponse
    {
        $response = ['test' => true];

        return $this->json($response, JsonResponse::HTTP_OK);
    }

    #[Route('/api/v1/account/avatar', name: 'member_account_avatar_upload', methods: ['POST'])]
    public function uploadAvatar(Request $request): JsonResponse
    {
        $response = ['test' => true];

        return $this->json($response, JsonResponse::HTTP_OK);
    }

    #[Route('/api/v1/account/avatar', name: 'member_account_avatar_delete', methods: ['DELETE'])]
    public function deleteAvatar(Request $request): JsonResponse
    {
        $response = ['test' => true];

        return $this->json($response, JsonResponse::HTTP_OK);
    }

    #[Route('/api/v1/account/posts', name: 'member_account_posts', methods: ['GET'])]
    public function listPosts(Request $request): JsonResponse
    {
        $response = ['test' => true];

        return $this->json($response, JsonResponse::HTTP_OK);
    }

    #[Route('/api/v1/account/notifications', name: 'member_account_notifications', methods: ['GET'])]
    public function listNotifications(Request $request): JsonResponse
    {
        $response = ['test' => true];

        return $this->json($response, JsonResponse::HTTP_OK);
    }

    #[Route('/api/v1/account/notifications/read', name: 'member_account_notification_read', methods: ['POST'])]
    public function setNotificationRead(Request $request): JsonResponse
    {
        $response = ['test' => true];

        return $this->json($response, JsonResponse::HTTP_OK);
    }
}