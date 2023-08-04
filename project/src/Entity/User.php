<?php

namespace App\Entity;

use App\EnumType\Groupe;
use App\EnumType\Role;
use App\EnumType\Status;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface 
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    private ?string $lastname = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?DateTime $birthday = null;

    #[ORM\Column(length: 8)]
    private ?string $phone = null;

    #[ORM\Column(length: 255)]
    private ?string $flag = null;

    #[ORM\Column(type: "string", enumType: Status::class)]
    private Status $status;

    #[ORM\Column(type: "string", enumType: Role::class)]
    private Role $role;

    #[ORM\Column(type: "string", enumType: Groupe::class)]
    private Groupe $groupe;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGroupe(): ?Groupe
    {
        return $this->groupe;
    }

    public function setGroupe(Groupe $groupe): static
    {
        $this->groupe = Groupe::ED;

        return $this;
    }

    public function getRole(): ?Role
    {
        return $this->role;
    }

    public function setRole(Role $role): static
    {
        $this->role = Role::User;

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(Status $status): static
    {
        $this->status = Status::Enabled;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }
    
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }
    
    public function getBirthday(): ?DateTime
    {
        return $this->birthday;
    }

    public function setBirthday(?DateTime $birthday): static
    {
        $this->birthday = $birthday;

        return $this;
    } 


    public function getFlag(): ?string
    {
        return $this->flag;
    }

    public function setFlag(string $flag): static
    {
        $this->flag = $flag;

        return $this;
    }


    public function getSalt()
    {
        // Return null if you're using modern password hashing (e.g., bcrypt)
        return null;
    }


    public function eraseCredentials()
    {
        // This method can be left empty unless you need to handle sensitive data
        // stored in the user object.
    }


    public function getRoles(): array
    {
        $roles = [$this->role->getValue()];

        $roles[] = Role::User->getValue(); 

        return array_unique($roles);
    }

    public function getUserIdentifier(): string
    {
        return (string)$this->email;
    }
}
