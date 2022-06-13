<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ChatRepository;
use App\Traits\Timestamps;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;

#[ORM\Entity(repositoryClass: ChatRepository::class)]
// #[ApiResource]
#[HasLifecycleCallbacks]
class Chat
{
    use Timestamps;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Conversation::class, inversedBy: 'chats')]
    #[ORM\JoinColumn(nullable: false)]
    private $conversation_id;

    #[ORM\Column(type: 'text', nullable: true)]
    private $message;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'chats')]
    #[ORM\JoinColumn(nullable: false)]
    private $by_user_id;

    #[ORM\PrePersist]
    public function onPrePersist()
    {
        $this->setCreated(new \DateTime());
    }

    #[ORM\PreUpdate]
    public function onPreUpdate()
    {
        $this->setUpdated(new \DateTime());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getConversationId(): ?Conversation
    {
        return $this->conversation_id;
    }

    public function setConversationId(?Conversation $conversation_id): self
    {
        $this->conversation_id = $conversation_id;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getByUserId(): ?User
    {
        return $this->by_user_id;
    }

    public function setByUserId(?User $by_user_id): self
    {
        $this->by_user_id = $by_user_id;

        return $this;
    }
}
