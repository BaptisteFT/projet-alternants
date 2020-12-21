<?php

namespace App\Entity;

use App\Repository\ApiTokenRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ApiTokenRepository::class)
 */
class ApiToken
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $token;

    /**
     * @ORM\OneToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $creator;

    /**
     * @ORM\Column(type="datetime")
     */
    private $expireDate;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="apiToken")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function __construct(User $user, User $creator)
    {
        $this->token = bin2hex(random_bytes(16));
        $this->user = $user;
        $this->expireDate = new \DateTime('+1 hour');
        $this->creator = $creator;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function getExpireDate(): ?\DateTime
    {
        return  $this->expireDate;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function generateUrl(): ?string
    {
        return "http://localhost:8000/login-token/".$this->getToken();
    }
}
