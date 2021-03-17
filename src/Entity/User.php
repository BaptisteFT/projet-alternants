<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=180)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=180)
     */
    private $lastName;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\OneToOne(targetEntity=ApiToken::class, mappedBy="user", orphanRemoval=true)
     */
    private $apiToken;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\OneToOne(targetEntity=Contract::class, mappedBy="user", orphanRemoval=true)
     */
    private $contract;

    /**
     * @ORM\Column(type="string", length=16, nullable=true)
     */
    /* Le statut permet de connaitre l'état de l'étudiant dans le cycle de l'application
    Liste des statuts possible :
    NULL : le compte n'est pas actif
    OTHER : l'utilisateur n'est pas un étudiant
    RESEARCH : l'étudiant est en recherche d'alternance
    CONTRACT_SEND : l'entreprise a remplie la pré-convention de l'étudiant
    WORKING : l'étudiant est actuellement en alternance */
    private $status;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $civility;

    /**
     * @ORM\ManyToOne (targetEntity=User::class, inversedBy="studentsTeacher")
     */
    private $teacher;

    /**
     * @ORM\OneToMany(targetEntity=Review::class, mappedBy="student", orphanRemoval=true)
     */
    private $reviews;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="studentsCompany")
     */
    private $company;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="company")
     */
    private $studentsCompany;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="teacher")
     */
    private $studentsTeacher;


    /**
     * @ORM\OneToMany(targetEntity=ApiToken::class, mappedBy="user")
     */
    private $apiTokens;




    public function __construct()
    {
        $this->apiTokens = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->studentsCompany = new ArrayCollection();
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

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status): void
    {
        $this->status = $status;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @see UserInterface
     */
    public function setFirstName($firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @see UserInterface
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @see UserInterface
     */
    public function setLastName($lastName): void
    {
        $this->lastName = $lastName;
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
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getToken()
    {
        return $this->apiToken;
    }

    public function setToken(ApiToken $apiToken): self
    {
        $this->apiToken = $apiToken;

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

    /**
     * @return mixed
     */
    public function getTeacher() : ?self
    {
        return $this->teacher;
    }

    /**
     * @param mixed $teacher
     */
    public function setTeacher( $teacher): void
    {
        $this->teacher = $teacher;
    }

    /**
     * @return Collection|Review[]
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): self
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews[] = $review;
            $review->setStudent($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getStudent() === $this) {
                $review->setStudent(null);
            }
        }

        return $this;
    }

    public function getCompany(): ?self
    {
        return $this->company;
    }

    public function setCompany(?self $company): self
    {
        $this->company = $company;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getStudentsCompany(): Collection
    {
        return $this->studentsCompany;
    }

    public function addStudentsCompany(self $studentsCompany): self
    {
        if (!$this->studentsCompany->contains($studentsCompany)) {
            $this->studentsCompany[] = $studentsCompany;
            $studentsCompany->setCompany($this);
        }

        return $this;
    }

    public function removeStudentsCompany(self $studentsCompany): self
    {
        if ($this->studentsCompany->removeElement($studentsCompany)) {
            // set the owning side to null (unless already changed)
            if ($studentsCompany->getCompany() === $this) {
                $studentsCompany->setCompany(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getStudentsTeacher(): Collection
    {
        return $this->studentsTeacher;
    }

    public function addStudentsTeacher(self $studentsTeacher): self
    {
        if (!$this->studentsTeacher->contains($studentsTeacher)) {
            $this->studentsTeacher[] = $studentsTeacher;
            $studentsTeacher->setTeacher($this);
        }

        return $this;
    }

    public function removeStudentsTeacher(self $studentsTeacher): self
    {
        if ($this->studentsTeacher->removeElement($studentsTeacher)) {
            // set the owning side to null (unless already changed)
            if ($studentsTeacher->getTeacher() === $this) {
                $studentsTeacher->setTeacher(null);
            }
        }

        return $this;
    }



    /**
     * @return Collection|ApiToken[]
     */
    public function getApiTokens(): Collection
    {
        return $this->apiTokens;
    }

    public function addApiToken(ApiToken $apiToken): self
    {
        if (!$this->apiTokens->contains($apiToken)) {
            $this->apiTokens[] = $apiToken;
            $apiToken->setUser($this);
        }

        return $this;
    }

    public function removeApiToken(ApiToken $apiToken): self
    {
        if ($this->apiTokens->removeElement($apiToken)) {
            // set the owning side to null (unless already changed)
            if ($apiToken->getUser() === $this) {
                $apiToken->setUser(null);
            }
        }

        return $this;
    }




}
