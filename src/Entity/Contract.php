<?php

namespace App\Entity;

use App\Repository\ContractRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ContractRepository::class)
 */
class Contract
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="contract")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;


    /**
     * @ORM\Column(type="string", length=64)
     */
    private $socialReason;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $siretNumber;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $activity;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $locationStreet;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $locationCity;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $postalCode;

    /**
     * @ORM\Column(type="string", length=16)
     */
    private $phoneNumber;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $contractEmail;

    /**
     * @ORM\Column(type="boolean")
     */
    private $representativeCivility;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $representativeFirstName;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $representativeLastName;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $representativeRole;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $representativeEmail;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $otherSocialReason;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $otherLocationStreet;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $otherLocationCity;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $otherPostalCode;

    /**
     * @ORM\Column(type="string", length=16, nullable=true)
     */
    private $otherPhoneNumber;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $workerRole;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $contractType;

    /**
     * @ORM\Column(type="datetime")
     */
    private $contractStartDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $contractEndDate;

    /**
     * @ORM\Column(type="boolean")
     */
    private $tutorCivility;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $tutorFirstName;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $tutorLastName;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $tutorRole;

    /**
     * @ORM\Column(type="string", length=16)
     */
    private $tutorPhoneNumber;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $tutorEmail;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSocialReason(): ?string
    {
        return $this->socialReason;
    }

    public function setSocialReason(string $socialReason): self
    {
        $this->socialReason = $socialReason;

        return $this;
    }

    public function getSiretNumber(): ?string
    {
        return $this->siretNumber;
    }

    public function setSiretNumber(string $siretNumber): self
    {
        $this->siretNumber = $siretNumber;

        return $this;
    }

    public function getActivity(): ?string
    {
        return $this->activity;
    }

    public function setActivity(string $activity): self
    {
        $this->activity = $activity;

        return $this;
    }

    public function getLocationStreet(): ?string
    {
        return $this->locationStreet;
    }

    public function setLocationStreet(string $locationStreet): self
    {
        $this->locationStreet = $locationStreet;

        return $this;
    }

    public function getLocationCity(): ?string
    {
        return $this->locationCity;
    }

    public function setLocationCity(string $locationCity): self
    {
        $this->locationCity = $locationCity;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getContractEmail(): ?string
    {
        return $this->contractEmail;
    }

    public function setContractEmail(?string $contractEmail): self
    {
        $this->contractEmail = $contractEmail;

        return $this;
    }

    public function getRepresentativeCivility(): ?bool
    {
        return $this->representativeCivility;
    }

    public function setRepresentativeCivility(bool $representativeCivility): self
    {
        $this->representativeCivility = $representativeCivility;

        return $this;
    }

    public function getRepresentativeFirstName(): ?string
    {
        return $this->representativeFirstName;
    }

    public function setRepresentativeFirstName(string $representativeFirstName): self
    {
        $this->representativeFirstName = $representativeFirstName;

        return $this;
    }

    public function getRepresentativeLastName(): ?string
    {
        return $this->representativeLastName;
    }

    public function setRepresentativeLastName(string $representativeLastName): self
    {
        $this->representativeLastName = $representativeLastName;

        return $this;
    }

    public function getRepresentativeRole(): ?string
    {
        return $this->representativeRole;
    }

    public function setRepresentativeRole(string $representativeRole): self
    {
        $this->representativeRole = $representativeRole;

        return $this;
    }

    public function getRepresentativeEmail(): ?string
    {
        return $this->representativeEmail;
    }

    public function setRepresentativeEmail(string $representativeEmail): self
    {
        $this->representativeEmail = $representativeEmail;

        return $this;
    }

    public function getOtherSocialReason(): ?string
    {
        return $this->otherSocialReason;
    }

    public function setOtherSocialReason(?string $otherSocialReason): self
    {
        $this->otherSocialReason = $otherSocialReason;

        return $this;
    }

    public function getOtherLocationStreet(): ?string
    {
        return $this->otherLocationStreet;
    }

    public function setOtherLocationStreet(?string $otherLocationStreet): self
    {
        $this->otherLocationStreet = $otherLocationStreet;

        return $this;
    }

    public function getOtherLocationCity(): ?string
    {
        return $this->otherLocationCity;
    }

    public function setOtherLocationCity(?string $otherLocationCity): self
    {
        $this->otherLocationCity = $otherLocationCity;

        return $this;
    }

    public function getOtherPostalCode(): ?string
    {
        return $this->otherPostalCode;
    }

    public function setOtherPostalCode(?string $otherPostalCode): self
    {
        $this->otherPostalCode = $otherPostalCode;

        return $this;
    }

    public function getOtherPhoneNumber(): ?string
    {
        return $this->otherPhoneNumber;
    }

    public function setOtherPhoneNumber(?string $otherPhoneNumber): self
    {
        $this->otherPhoneNumber = $otherPhoneNumber;

        return $this;
    }

    public function getWorkerRole(): ?string
    {
        return $this->workerRole;
    }

    public function setWorkerRole(string $workerRole): self
    {
        $this->workerRole = $workerRole;

        return $this;
    }

    public function getContractType(): ?string
    {
        return $this->contractType;
    }

    public function setContractType(string $contractType): self
    {
        $this->contractType = $contractType;

        return $this;
    }

    public function getContractStartDate(): ?\DateTimeInterface
    {
        return $this->contractStartDate;
    }

    public function setContractStartDate(\DateTimeInterface $contractStartDate): self
    {
        $this->contractStartDate = $contractStartDate;

        return $this;
    }

    public function getContractEndDate(): ?\DateTimeInterface
    {
        return $this->contractEndDate;
    }

    public function setContractEndDate(\DateTimeInterface $contractEndDate): self
    {
        $this->contractEndDate = $contractEndDate;

        return $this;
    }

    public function getTutorCivility(): ?bool
    {
        return $this->tutorCivility;
    }

    public function setTutorCivility(bool $tutorCivility): self
    {
        $this->tutorCivility = $tutorCivility;

        return $this;
    }

    public function getTutorFirstName(): ?string
    {
        return $this->tutorFirstName;
    }

    public function setTutorFirstName(string $tutorFirstName): self
    {
        $this->tutorFirstName = $tutorFirstName;

        return $this;
    }

    public function getTutorLastName(): ?string
    {
        return $this->tutorLastName;
    }

    public function setTutorLastName(string $tutorLastName): self
    {
        $this->tutorLastName = $tutorLastName;

        return $this;
    }

    public function getTutorRole(): ?string
    {
        return $this->tutorRole;
    }

    public function setTutorRole(string $tutorRole): self
    {
        $this->tutorRole = $tutorRole;

        return $this;
    }

    public function getTutorPhoneNumber(): ?string
    {
        return $this->tutorPhoneNumber;
    }

    public function setTutorPhoneNumber(string $tutorPhoneNumber): self
    {
        $this->tutorPhoneNumber = $tutorPhoneNumber;

        return $this;
    }

    public function getTutorEmail(): ?string
    {
        return $this->tutorEmail;
    }

    public function setTutorEmail(string $tutorEmail): self
    {
        $this->tutorEmail = $tutorEmail;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }






}
