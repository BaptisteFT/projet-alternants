<?php

namespace App\Entity;

use App\Repository\ContractBase64Repository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ContractBase64Repository::class)
 */
class ContractBase64
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=500000)
     */
    private $base64;

    /**
     * @ORM\OneToOne(targetEntity=Contract::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $contract;

    public function __construct(Contract $contract, string $base64)
    {
        $this->base64 = $base64;
        $this->contract = $contract;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBase64(): ?string
    {
        return $this->base64;
    }

    public function setBase64(string $base64): self
    {
        $this->base64 = $base64;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getContract()
    {
        return $this->contract;
    }

    /**
     * @param mixed $contract
     */
    public function setContract($contract): void
    {
        $this->contract = $contract;
    }


}
