<?php

namespace App\Entity;

use App\Repository\ApiTokenRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @ORM\Column(type="datetime")
     */
    private $expireDate;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;


    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="apiTokens")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $creator;



    public function __construct(User $user, User $creator, bool $isAlreadyUsed)
    {
        if ($isAlreadyUsed)
        {
            $this->expireDate = new \DateTime();
            $this->creator = $creator;
            $this->token = bin2hex(random_bytes(16));
            $this->user = $user;
            $this->setIsActive(true);
        }
        else
        {
            $timeZone = new \DateTimeZone('Europe/Paris');
            $datetime = new \DateTime();
            $datetime->setTimezone($timeZone);
            $datetime->modify('+7 day');
            $this->token = bin2hex(random_bytes(16));
            $this->user = $user;
            $this->expireDate = $datetime;
            $this->creator = $creator;
            $this->setIsActive(false);
        }
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
        return "/login-token/".$this->getToken();
    }

    /**
     * @return mixed
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * @param mixed $isActive
     */
    public function setIsActive($isActive): void
    {
        $this->isActive = $isActive;
    }



    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCreator(): ?User
    {
        return $this->creator;
    }

    public function setCreator(?User $creator): self
    {
        $this->creator = $creator;

        return $this;
    }





}
