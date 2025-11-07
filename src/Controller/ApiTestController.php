<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use App\Message\NotifyUserMessage;
use Symfony\Component\Messenger\MessageBusInterface;
use App\Domain\Geo\Repository\GeoRegionRepository;
use App\Domain\Geo\Repository\GeoContinentRepository;

class ApiTestController extends AbstractController
{
    public function __construct(
        private MessageBusInterface $messageBus
    ) {}

    #[Route('/api/v1', name: 'installation_test_v1')]
    public function testV1(): JsonResponse
    {
        $data = [
            'message' => 'REST API version 1.',
            'datetime' => (new \DateTime())->format('Y-m-d H:i:s'),
        ];

        return $this->json($data, JsonResponse::HTTP_OK);
    }

    #[Route('/api/v1/query', name: 'installation_test_query')]
    public function query(GeoRegionRepository $geoRegion): JsonResponse //GeoContinentRepository $geoContinent
    {
        $result = $geoRegion->findIdContinentNameAndRegionName('Europe', 'Western');

        $data = [
            'message' => ['result' => $result],
            'datetime' => (new \DateTime())->format('Y-m-d H:i:s'),
        ];

        return $this->json($data, JsonResponse::HTTP_OK);
    }

    #[Route('/api/v1/test-mail', name: 'installation_test_mail')]
    public function sendTest(MailerInterface $mailer): JsonResponse
    {
        $email = (new Email())
            ->from('no-reply@example.com')
            ->to('dev@example.com')
            ->subject('Welcome to My App')
            ->text('Testing email');

        $success = false;
        $error = null;

        try {
            $mailer->send($email);
            $success = true;
        } catch (TransportExceptionInterface $e) {
            // Transport errors (connection, timeouts, TLS, auth)
            $error = $e->getMessage();
        } catch (\Throwable $e) {
            // Any other unexpected error
            $error = $e->getMessage();
        }

        $data = [
            'message' => $success ? 'Email sent' : 'Email failed',
            'success' => $success,
            'error' => $error,
            'timestamp' => (new \DateTime())->format(\DateTime::ATOM),
        ];

        return $this->json($data, JsonResponse::HTTP_CREATED);
    }

    #[Route('/api/v1/test-queue', name: 'installation_test_queue')]
    public function testQueue(): JsonResponse
    {
        $userId = 1;
        $success = false;
        $error = null;

        try {
            // Dispatch returns an Envelope; dispatch itself does not mean the job was processed,
            // only that it was accepted for transport.
            $envelope = $this->messageBus->dispatch(new NotifyUserMessage($userId, 'Welcome!'));
            $success = true;
        } catch (\Throwable $e) {
            $error = $e->getMessage();
        }

        $data = [
            'message' => $success ? 'Message dispatched' : 'Message failed',
            'success' => $success,
            'error' => $error,
            'timestamp' => (new \DateTime())->format(\DateTime::ATOM),
        ];

        return $this->json($data, JsonResponse::HTTP_ACCEPTED);
    }
}
