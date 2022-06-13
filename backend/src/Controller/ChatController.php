<?php

namespace App\Controller;

use App\Entity\Chat;
use App\Entity\Conversation;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/chat')]
class ChatController extends AbstractController
{
    private $tokenStorage;

    private $manager;

    private $serializer;

    public function __construct(TokenStorageInterface $tokenStorage, EntityManagerInterface $manager, SerializerInterface $serializer)
    {
        $this->tokenStorage = $tokenStorage;
        $this->manager = $manager;
        $this->serializer = $serializer;
    }

    public function getLoggedInUser(): ?User
    {
        $token = $this->tokenStorage->getToken();

        if (!$token) {
            return null;
        }

        $user = $token->getUser();

        if (!$user instanceof User) {
            return null;
        }

        return $user;
    }

    #[Route('/conversations', name: 'user_conversations')]
    public function index()
    {
        $user = $this->getLoggedInUser();
        if (!$user) {
            return new JsonResponse(['error' => 'User not found'], Response::HTTP_UNAUTHORIZED);
        }

        $conversation = $this->manager->getRepository(Conversation::class)->getConversations($user);
        // $conversation = $this->manager->getRepository(Conversation::class)->findBy(['user' => $user]);
        $json_object = $this->serializer->serialize($conversation, JsonEncoder::FORMAT, [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            },
        ]);
        return new JsonResponse($conversation, 200, []);
    }

    #[Route('/messages/{conversation}', name: 'conversation_chat')]
    public function messages(Conversation $conversation)
    {
        $user = $this->getLoggedInUser();
        if (!$user) {
            return new JsonResponse(['error' => 'User not found'], Response::HTTP_UNAUTHORIZED);
        }

        if ($conversation === null || $conversation === '' || !$conversation instanceof Conversation) {
            return new JsonResponse(['error' => 'Conversation not found'], Response::HTTP_NOT_FOUND);
        }

        $conversationChats = $this->manager->getRepository(Chat::class)->getConversationChat($conversation);
        // $conversationChats = $conversation->getChats();
        $json_object = $this->serializer->serialize($conversationChats, JsonEncoder::FORMAT, [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            },
        ]);
        return new JsonResponse($json_object, 200, [], true);
    }
}
