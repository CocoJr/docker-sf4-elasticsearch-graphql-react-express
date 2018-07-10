<?php
/**
 * @author Thibault Colette <thibaultcolette06@hotmail.fr>
 * @copyright 2018 Thibault Colette
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 *
 * @UniqueEntity(fields="email", message="email.exist")
 * @UniqueEntity(fields="username", message="username.exist")
 *
 * @Serializer\ExclusionPolicy("all")
 */
class User implements AdvancedUserInterface
{
    const ADMIN_EMAILS = [
        'thibaultcolette06@hotmail.fr',
    ];

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Serializer\Groups({"elastica"})
     * @Serializer\Expose()
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25, unique=true)
     *
     * @Assert\NotBlank()
     *
     * @Serializer\Groups({"elastica"})
     * @Serializer\Expose()
     */
    private $username;

    /**
     * @Assert\NotBlank(groups={"user_register"})
     * @Assert\Length(max=128)
     */
    private $plainPassword;

    /**
     * @Assert\EqualTo(propertyPath="plainPassword")
     */
    private $passwordConfirm;

    /**
     * @ORM\Column(type="string", length=64)
     *
     * @Serializer\Groups({"elastica"})
     * @Serializer\Expose()
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     *
     * @Assert\NotBlank()
     * @Assert\Email()
     *
     * @Serializer\Groups({"elastica"})
     * @Serializer\Expose()
     */
    private $email;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     *
     * @Serializer\Groups({"elastica"})
     * @Serializer\Expose()
     */
    private $isActive;

    /**
     * @var bool
     *
     * @Assert\EqualTo(true)
     */
    private $termsAccepted;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Serializer\Groups({"elastica"})
     * @Serializer\Expose()
     */
    private $termsAcceptedAt;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Serializer\Groups({"elastica"})
     * @Serializer\Expose()
     */
    private $registratedAt;

    /**
     * @ORM\Column(name="roles", type="array", nullable=false)
     *
     * @Serializer\Groups({"elastica"})
     * @Serializer\Expose()
     */
    private $roles;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AuthToken", mappedBy="user", cascade={"persist", "remove", "merge"})
     */
    protected $authTokens;

    /**
     * @ORM\OneToOne(targetEntity="File", cascade={"persist", "remove"}, fetch="EAGER")
     * @Assert\Valid(groups={"upload_img"})
     *
     * @Serializer\Groups({"elastica"})
     * @Serializer\Expose()
     */
    private $imgProfil;

    /**
     * return void
     *
     * @codeCoverageIgnore
     */
    public function eraseCredentials()
    {
    }

    /**
     * @return true
     *
     * @codeCoverageIgnore
     */
    public function isAccountNonExpired()
    {
        return true;
    }

    /**
     * @return true
     *
     * @codeCoverageIgnore
     */
    public function isAccountNonLocked()
    {
        return true;
    }

    /**
     * @return true
     *
     * @codeCoverageIgnore
     */
    public function isCredentialsNonExpired()
    {
        return true;
    }

    /**
     * @return null
     *
     * @codeCoverageIgnore
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return $this->getIsActive();
    }

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->setRoles([]);
        $this->setIsActive(true);
        $this->setRegistratedAt(new \DateTime());
        $this->authTokens = new ArrayCollection();

        $this->addRole('ROLE_USER');
    }

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param $password
     *
     * @return User
     */
    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;

        return $this;
    }

    /**
     * @return string|null
     *
     * @codeCoverageIgnore
     */
    public function getPasswordConfirm()
    {
        return $this->passwordConfirm;
    }

    /**
     * @param $passwordConfirm
     *
     * @return $this
     */
    public function setPasswordConfirm($passwordConfirm)
    {
        $this->passwordConfirm = $passwordConfirm;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return bool
     *
     * @codeCoverageIgnore
     */
    public function getTermsAccepted()
    {
        return $this->termsAccepted;
    }

    /**
     * @param $termsAccepted
     *
     * @return User
     */
    public function setTermsAccepted($termsAccepted)
    {
        $this->termsAccepted = $termsAccepted;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * @param $isActive
     *
     * @return User
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getTermsAcceptedAt(): ?\DateTimeInterface
    {
        return $this->termsAcceptedAt;
    }

    /**
     * @param \DateTimeInterface $termsAcceptedAt
     *
     * @return User
     */
    public function setTermsAcceptedAt(\DateTimeInterface $termsAcceptedAt): self
    {
        $this->termsAcceptedAt = $termsAcceptedAt;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getRegistratedAt(): ?\DateTimeInterface
    {
        return $this->registratedAt;
    }

    /**
     * @param \DateTimeInterface $registratedAt
     *
     * @return User
     */
    public function setRegistratedAt(\DateTimeInterface $registratedAt): self
    {
        $this->registratedAt = $registratedAt;

        return $this;
    }

    /**
     * @param array $roles
     *
     * @return User
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @param string $role
     *
     * @return User
     */
    public function addRole($role): self
    {
        $this->roles[] = $role;

        return $this;
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param $role
     *
     * @return bool
     */
    public function hasRole($role)
    {
        return in_array($role, $this->getRoles());
    }

    /**
     * Add authToken
     *
     * @param \App\Entity\AuthToken $authToken
     *
     * @return User
     */
    public function addAuthToken(\App\Entity\AuthToken $authToken)
    {
        $this->authTokens[] = $authToken;

        return $this;
    }

    /**
     * Remove authToken
     *
     * @param \App\Entity\AuthToken $authToken
     *
     * @codeCoverageIgnore
     */
    public function removeAuthToken(\App\Entity\AuthToken $authToken)
    {
        $this->authTokens->removeElement($authToken);
    }

    /**
     * Get authTokens
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAuthTokens()
    {
        return $this->authTokens;
    }

    public function getImgProfil(): ?File
    {
        return $this->imgProfil;
    }

    public function setImgProfil(?File $imgProfil): self
    {
        $this->imgProfil = $imgProfil;

        return $this;
    }
}
