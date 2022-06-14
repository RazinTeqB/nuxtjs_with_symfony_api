<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use App\Traits\Timestamps;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource(
    normalizationContext: ["groups" => ["read"]],
    denormalizationContext: ["groups" => ["write"]],
)]
#[ApiFilter(SearchFilter::class, properties: ['username' => 'ipartial'])]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity(fields: ['username'], message: 'This email is already in use.')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use Timestamps;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: 'integer')]
    #[Groups(["read"])]
    private $id;

    // #[ORM\Column(type: 'string', length: 180, unique: true)]
    // private $email;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["read", "write"])]
    private $name;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Groups(["read", "write"])]
    private $username;

    #[ORM\Column(type: 'json')]
    #[Groups(["read", "write"])]
    private $roles = [];

    #[ORM\Column(type: 'string')]
    #[Groups(["read", "write"])]
    private $password;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Student::class)]
    // #[Groups(["write"])]
    private $students;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Conversation::class, orphanRemoval: true)]
    private $conversations;

    #[ORM\OneToMany(mappedBy: 'conv_with_user', targetEntity: Conversation::class)]
    private $conversations_with;

    #[ORM\OneToMany(mappedBy: 'by_user', targetEntity: Chat::class)]
    private $chats;

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

    public function __construct()
    {
        $this->students = new ArrayCollection();
        $this->conversations = new ArrayCollection();
        $this->conversations_with = new ArrayCollection();
        $this->chats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }



    /**
     * @return Collection<int, Student>
     */
    public function getStudents(): Collection
    {
        return $this->students;
    }

    public function addStudent(Student $student): self
    {
        if (!$this->students->contains($student)) {
            $this->students[] = $student;
            $student->setUser($this);
        }

        return $this;
    }

    public function removeStudent(Student $student): self
    {
        if ($this->students->removeElement($student)) {
            // set the owning side to null (unless already changed)
            if ($student->getUser() === $this) {
                $student->setUser(null);
            }
        }

        return $this;
    }

    // public function __toString()
    // {
    //     return $this->getUsername();
    // }

    /**
     * @return Collection<int, Conversation>
     */
    public function getConversations(): Collection
    {
        return $this->conversations;
    }

    public function addConversation(Conversation $conversation): self
    {
        if (!$this->conversations->contains($conversation)) {
            $this->conversations[] = $conversation;
            $conversation->setUser($this);
        }

        return $this;
    }

    public function removeConversation(Conversation $conversation): self
    {
        if ($this->conversations->removeElement($conversation)) {
            // set the owning side to null (unless already changed)
            if ($conversation->getUser() === $this) {
                $conversation->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Conversation>
     */
    public function getConversationsWith(): Collection
    {
        return $this->conversations_with;
    }

    public function addConversationsWith(Conversation $conversationsWith): self
    {
        if (!$this->conversations_with->contains($conversationsWith)) {
            $this->conversations_with[] = $conversationsWith;
            $conversationsWith->setConvWithUser($this);
        }

        return $this;
    }

    public function removeConversationsWith(Conversation $conversationsWith): self
    {
        if ($this->conversations_with->removeElement($conversationsWith)) {
            // set the owning side to null (unless already changed)
            if ($conversationsWith->getConvWithUser() === $this) {
                $conversationsWith->setConvWithUser(null);
            }
        }

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
            $chat->setByUser($this);
        }

        return $this;
    }

    public function removeChat(Chat $chat): self
    {
        if ($this->chats->removeElement($chat)) {
            // set the owning side to null (unless already changed)
            if ($chat->getByUser() === $this) {
                $chat->setByUser(null);
            }
        }

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
