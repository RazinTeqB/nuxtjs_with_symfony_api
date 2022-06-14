<?php

namespace App\Controller;

use App\Entity\Chat;
use App\Entity\Conversation;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Pusher\Pusher;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

use function GuzzleHttp\Promise\all;

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

    #[Route('/conversations', name: 'user_conversations', methods: ['GET'])]
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

    #[Route('/conversations', name: 'new_conversation', methods: ['POST'])]
    public function new_conversation(Request $request, Pusher $pusher)
    {
        $user = $this->getLoggedInUser();
        if (!$user) {
            return new JsonResponse(['error' => 'User not found'], Response::HTTP_UNAUTHORIZED);
        }
        $data = json_decode($request->getContent(), true);
        if ($user->getId() === $data['id']) {
            return new JsonResponse(['error' => 'You can not chat with yourself'], Response::HTTP_UNAUTHORIZED);
        }
        $convWithUser = $this->manager->getRepository(User::class)->find($data['id']);
        $conversation = $this->manager->getRepository(Conversation::class)->findOneBy([
            'user' => $user->getId(),
            'conv_with_user' => $convWithUser->getId(),
        ]);
        if (!$conversation) {
            $inverseConversation = $this->manager->getRepository(Conversation::class)->findOneBy([
                'user' => $convWithUser->getId(),
                'conv_with_user' => $user->getId(),
            ]);
            if (!$inverseConversation) {
                $conversation = new Conversation();
                $conversation->setUser($user);
                $conversation->setConvWithUser($convWithUser);
                $this->manager->persist($conversation);
                $this->manager->flush();
            } else {
                $conversation = $inverseConversation;
            }
        }

        $pusher->trigger('user-' . $user->getId(), 'conversationsUpdate', [
            'message' => 'Conversation Created!',
            'data' => [
                'id' => $conversation->getId(),
                'conv_with_user' => $convWithUser->getName(),
            ],
            'openChat' => true
        ]);
        $pusher->trigger('user-' . $convWithUser->getId(), 'conversationsUpdate', [
            'message' => 'Conversation Created!',
            'data' => [
                'id' => $conversation->getId(),
                'conv_with_user' => $user->getName(),
            ],
        ]);
        return new JsonResponse(['message' => 'Conversation Created!'], Response::HTTP_CREATED, []);
    }

    #[Route('/messages/{conversation}', name: 'conversation_chat', methods: ['GET'])]
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

    #[Route('/messages/{conversation}', name: 'chat_message', methods: ['POST'])]
    public function messages_post(Conversation $conversation, Request $request, Pusher $pusher)
    {
        $user = $this->getLoggedInUser();
        if (!$user) {
            return new JsonResponse(['error' => 'User not found'], Response::HTTP_UNAUTHORIZED);
        }
        $data = json_decode($request->getContent(), true);
        if ($data['message'] !== '') {
            $chat = new Chat();
            $chat->setMessage($data['message']);
            $chat->setByUser($user);
            $chat->setConversation($conversation);
            $this->manager->persist($chat);
            $this->manager->flush();
            $entityAsArray = $this->serializer->normalize($chat, null, [
                'circular_reference_handler' => function ($object) {
                    return $object->getId();
                },
            ]);
            $pusher->trigger('conversation-' . $conversation->getId(), 'chat', [
                'message' => 'Message sent!',
                'data' => [
                    'id' => $entityAsArray['id'],
                    'message' => $entityAsArray['message'],
                    'by_user' => $entityAsArray['byUser']['id'],
                    'created' => $entityAsArray['created'],
                ],
            ]);
            return new JsonResponse(['message' => 'Message sent'], Response::HTTP_CREATED, []);
        }
        return new JsonResponse($data, Response::HTTP_NO_CONTENT, [], false);
    }
}
