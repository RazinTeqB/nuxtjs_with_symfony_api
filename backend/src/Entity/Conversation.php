<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ConversationRepository;
use App\Traits\Timestamps;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;

#[ORM\Entity(repositoryClass: ConversationRepository::class)]
// #[ApiResource]
#[HasLifecycleCallbacks]
class Conversation
{
    use Timestamps;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'conversations')]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'conversations_with')]
    #[ORM\JoinColumn(nullable: false)]
    private $conv_with_user;

    #[ORM\OneToMany(mappedBy: 'conversation', targetEntity: Chat::class, orphanRemoval: true)]
    private $chats;

    public function __construct()
    {
        $this->chats = new ArrayCollection();
    }

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getConvWithUser(): ?User
    {
        return $this->conv_with_user;
    }

    public function setConvWithUser(?User $conv_with_user): self
    {
        $this->conv_with_user = $conv_with_user;

        return $this;
    }

    /**
     * @return Collection<int, Chat>
     */
    public function getChats(): Collection
    {
        return $this->chats;
    }

    public function addChat(Chat $chat): self
    {
        if (!$this->chats->contains($chat)) {
            $this->chats[] = $chat;
            $chat->setConversation($this);
        }

        return $this;
    }

    public function removeChat(Chat $chat): self
    {
        if ($this->chats->removeElement($chat)) {
            // set the owning side to null (unless already changed)
            if ($chat->getConversation() === $this) {
                $chat->setConversation(null);
            }
        }

        return $this;
    }
}
