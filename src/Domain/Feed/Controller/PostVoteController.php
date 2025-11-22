<?php

namespace App\Domain\Post\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class PostVoteController extends AbstractController
{
    #[Route('/api/v1/posts/{post_id}/votes', name: 'posts_votes_listing', methods: ['GET'])]
    public function listSections(Request $request): JsonResponse
    {
        $response = ['test' => true];

        return $this->json($response, JsonResponse::HTTP_OK);
    }
}