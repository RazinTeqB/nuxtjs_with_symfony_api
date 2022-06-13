<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Pusher\Pusher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class AuthController extends AbstractController
{
    private $tokenStorage;

    private $encoder;

    private $manager;

    public function __construct(TokenStorageInterface $tokenStorage, UserPasswordHasherInterface $encoder, EntityManagerInterface $manager)
    {
        $this->tokenStorage = $tokenStorage;
        $this->encoder = $encoder;
        $this->manager = $manager;
    }

    #[Route('/api/user', name: 'api_user', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $token = $this->tokenStorage->getToken();

        if (!$token) {
            return null;
        }

        $user = $token->getUser();

        if (!$user instanceof User) {
            return null;
        }

        return new JsonResponse([
            'data' => [
                'id' => $user->getId(),
                'username' => $user->getUsername(),
                'roles' => $user->getRoles(),
            ]
        ]);
    }

    #[Route('/api/register', name: 'register', methods: ['POST'])]
    public function register(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $statusCode = 200;
        if ($data['username'] != '' && $data['password'] != '') {
            $user = $this->manager->getRepository(User::class)->findOneBy(['username' => $data['username']]);
            if ($user == null || $user == '') {
                $user = new User();
                $user->setUsername($data['username']);
                $user->setPassword(
                    $this->encoder->hashPassword($user, $data['password'])
                );
                $user->setRoles(['ROLE_ADMIN']);
                $this->manager->persist($user);
                $this->manager->flush();
                $userId = $user->getId();
                $response = [
                    '@context' => '/api/contexts/User',
                    '@id' => '/api/user/' . $userId,
                    '@type' => 'User',
                    'id' => $userId,
                    'username' => $data['username'],
                    'created' => $user->getCreated() != null ? $user->getCreated()->format('Y-m-d H:i:s') : null,
                    'updated' => $user->getUpdated() != null ? $user->getUpdated()->format('Y-m-d H:i:s') : null,
                ];
            } else {
                $response = [
                    '@context' => '/api/register/ConstraintViolationList',
                    '@type' => 'ConstraintViolationList',
                    'hydra:title' => 'An error occurred',
                    'hydra:description' => 'email: Email already exist',
                    'violations' =>
                    [
                        [
                            'propertyPath' => 'email',
                            'message' => 'Email already exist',
                        ],
                    ],
                ];
                $statusCode = 422;
            }
        } else {
            $response = [
                '@context' => '/api/register/ConstraintViolationList',
                '@type' => 'ConstraintViolationList',
                'hydra:title' => 'An error occurred',
                'hydra:description' => 'email: Please enter an email',
                'violations' =>
                [
                    [
                        'propertyPath' => 'email',
                        'message' => 'Please enter an email',
                    ],
                ],
            ];
            $statusCode = 422;
        }
        return new JsonResponse($response, $statusCode);
    }

    #[Route('/api/say-hello', name: 'say_hello', methods: ['GET'])]
    public function sayHello(Pusher $pusher): Response
    {
        $pusher->trigger('channel1', 'new-greeting', [
            'message' => 'Hello world!'
        ]);

        return new Response();
    }
}
