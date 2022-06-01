<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\StudentRepository;
use Gedmo\Mapping\Annotation as Gedmo;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: StudentRepository::class)]
#[ApiResource]
#[ORM\HasLifecycleCallbacks]
#[ApiFilter(DateFilter::class, properties: ['dob'])]
#[UniqueEntity(fields: ['email'], message: 'This email is already in use.')]

class Student
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: "Please enter a name")]
    private $name;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    #[Assert\Email(message: "The email '{{ value }}' is not a valid email.")]
    #[Assert\NotBlank(message: "Please enter an email")]
    private $email;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: "Please select gender")]
    private $gender;

    #[ORM\Column(type: 'date')]
    #[Assert\NotBlank(message: "Please select date of birth")]
    private $dob;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $image;

    #[Gedmo\Timestampable(on: 'create')]
    #[ORM\Column(type: 'datetime', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private $created;

    #[Gedmo\Timestampable]
    #[ORM\Column(type: 'datetime', nullable: true)]
    private $updated;


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
}
