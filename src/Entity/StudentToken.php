<?php

namespace App\Entity;

use App\Repository\StudentTokenRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StudentTokenRepository::class)
 */
class StudentToken
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
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="studentToken", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $student;

    public function __construct(User $student){
        $timeZone = new \DateTimeZone('Europe/Paris');
        $datetime = new \DateTime();
        $datetime->setTimezone($timeZone);
        $datetime->modify('+7 day');
        $this->expireDate = $datetime;
        $this->token = bin2hex(random_bytes(16));
        $this->student = $student;
        $this->isActive = false;
    }

    public function generateUrl(): ?string
    {
        return "/login-student-token/".$this->getToken();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getExpireDate(): ?\DateTimeInterface
    {
        return $this->expireDate;
    }

    public function setExpireDate(\DateTimeInterface $expireDate): self
    {
        $this->expireDate = $expireDate;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getStudent(): ?User
    {
        return $this->student;
    }

    public function setStudent(User $student): self
    {
        $this->student = $student;

        return $this;
    }
}
