<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\StudentRepository;
use Gedmo\Mapping\Annotation as Gedmo;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use App\Controller\StudentController;

// File Upload Reference https://digitalfortress.tech/php/file-upload-with-api-platform-and-symfony/
#[ORM\Entity(repositoryClass: StudentRepository::class)]
#[ApiResource(
    normalizationContext: ["groups" => ["read"]],
    denormalizationContext: ["groups" => ["write"]],
    itemOperations: ["get", "patch", "put", "delete"],
    collectionOperations: [
        "get",
        "post" => [
            "controller" => StudentController::class,
            "deserialize" => false,
            "opeapi_context" => [
                "requestBody" => [
                    "description" => "File upload to student",
                    "required" => true,
                    "content" => [
                        "multipart/form-data" => [
                            "schema" => [
                                "type" => "object",
                                "properties" => [
                                    "name" => [
                                        "type" => "string",
                                        "description" => "Student name",
                                        "example" => "John Doe",
                                        "required" => true,
                                    ],
                                    "email" => [
                                        "type" => "string",
                                        "description" => "Student email",
                                        "example" => "demo@email.com",
                                        "required" => true,
                                    ],
                                    "gender" => [
                                        "type" => "string",
                                        "description" => "Student gender",
                                        "example" => "male|female|other",
                                        "required" => true,
                                    ],
                                    "dob" => [
                                        "type" => "string",
                                        "description" => "Student date of birth",
                                        "example" => "2000-12-31",
                                        "required" => true,
                                    ],
                                    "image" => [
                                        "type" => "string",
                                        "format" => "binary",
                                        "description" => "Student image file",
                                    ],

                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ],
)]
#[ORM\HasLifecycleCallbacks]
#[ApiFilter(DateFilter::class, properties: ['dob'])]
#[ApiFilter(SearchFilter::class, properties: ['name' => 'ipartial', 'email' => 'ipartial', 'gender' => 'istart'])]
#[UniqueEntity(fields: ['email'], message: 'This email is already in use.')]

class Student
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: 'integer')]
    #[Groups(["read"])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: "Please enter a name")]
    #[Groups(["read", "write"])]
    private $name;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    #[Assert\Email(message: "The email '{{ value }}' is not a valid email.")]
    #[Assert\NotBlank(message: "Please enter an email")]
    #[Groups(["read", "write"])]
    private $email;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: "Please select gender")]
    #[Groups(["read", "write"])]
    private $gender;

    #[ORM\Column(type: 'date')]
    #[Assert\NotBlank(message: "Please select date of birth")]
    #[Groups(["read", "write"])]
    private $dob;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(["read", "write"])]
    #[ApiProperty(
        iri: "http://schema.org/image",
        attributes: [
            "openapi_context" => [
                "type" => "file",
            ]
        ]
    )]
    private $image;

    #[Gedmo\Timestampable(on: 'create')]
    #[ORM\Column(type: 'datetime', options: ['default' => 'CURRENT_TIMESTAMP'])]
    #[Groups(["read"])]
    private $created;

    #[Gedmo\Timestampable]
    #[ORM\Column(type: 'datetime', nullable: true)]
    #[Groups(["read"])]
    private $updated;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'students', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: "UserId", referencedColumnName: "id", nullable: true, onDelete: 'SET NULL')]
    #[Groups(["read", "write"])]
    private $user;


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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function isGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getDob(): ?string
    {
        return $this->dob->format('Y-m-d');
    }

    public function setDob(?\DateTime $dob): self
    {
        $this->dob = $dob;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getCreated(): ?\DateTime
    {
        return $this->created;
    }

    public function setCreated(\DateTime $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getUpdated(): ?\DateTime
    {
        return $this->updated;
    }

    public function setUpdated(\DateTime $updated): self
    {
        $this->updated = $updated;

        return $this;
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
}
