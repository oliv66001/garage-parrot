<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'Il existe déjà un compte avec cet email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    private ?string $username = null;

    
    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min: 8, minMessage: 'Votre mot de passe doit contenir au moins 8 caractères')]
    
    private ?string $password = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column]
    private ?bool $isVerified = false;


    #[ORM\Column(length: 100, nullable: true)]
    private ?string $resetToken = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get the value of roles
     *
     * @return array
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
     * A visual identifier that represents this users.
     *
     * @see UsersInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

      /**
     * @see UsersInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * Get the value of username
     *
     * @return ?string
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * Set the value of username
     *
     * @param ?string $username
     *
     * @return self
     */
    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get the value of isVerified
     *
     * @return ?bool
     */
    public function getIsVerified(): ?bool
    {
        return $this->isVerified;
    }

    /**
     * Set the value of isVerified
     *
     * @param ?bool $isVerified
     *
     * @return self
     */
    public function setIsVerified(?bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

      /**
     * Get the value of resetToken
     *
     * @return ?string
     */
    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    /**
     * Set the value of resetToken
     *
     * @param ?string $resetToken
     *
     * @return self
     */
    public function setResetToken(?string $resetToken): self
    {
        $this->resetToken = $resetToken;

        return $this;
    }

    public function serialize()
    {
        return serialize($this->id);
    }

    public function unserialize($data)
    {
        $this->id = unserialize($data);
    }
}
