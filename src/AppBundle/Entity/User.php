<?php

namespace AppBundle\Entity;

use BlogBundle\Entity\Article;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ShopBundle\Entity\Order;
use ShopBundle\Entity\ProductUsers;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @UniqueEntity(fields="email", message="Email already taken")
 * @UniqueEntity(fields="username", message="Username already taken")
 *
 */
class User implements AdvancedUserInterface, \Serializable {
	/**
	 * @var int
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="username", type="string", length=255, unique=true)
	 *
	 * @Assert\NotBlank()
	 * @Assert\Length(
	 *      min = 3,
	 *      max = 30,
	 *      minMessage = "Your username must be at least {{ limit }} characters long",
	 *      maxMessage = "Your username cannot be longer than {{ limit }} characters"
	 * )
	 * @Assert\Type(
	 *     type="string",
	 *     message="The value {{ value }} is not a valid {{ type }}."
	 * )
	 */
	private $username;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="first_name",type="string",length=255 ,nullable=true)
	 *
	 */
	private $firstName;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="last_name",type="string",length=255 ,nullable=true)
	 *
	 */
	private $lastName;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="email", type="string", length=255, unique=true)
	 * @Assert\NotBlank()
	 * @Assert\Email(
	 *     message = "The email '{{ value }}' is not a valid email."
	 * )
	 */
	private $email;

	/**
	 * @var string
	 *
	 * @Assert\NotBlank(groups={"change_password"})
	 * @Assert\Length(
	 *     min = 6,
	 *     max = 4096,
	 *     minMessage = "Your password must be at least {{ limit }} characters long",
	 *     maxMessage = "Your password cannot be longer than {{ limit }} characters",
	 *     groups={"change_password"}
	 *     )
	 */
	private $oldPassword;

	/**
	 * @Assert\NotBlank(groups={"registration"})
	 * @Assert\Length(
	 *     min = 6,
	 *     max = 4096,
	 *     minMessage = "Your password must be at least {{ limit }} characters long",
	 *     maxMessage = "Your password cannot be longer than {{ limit }} characters",
	 *     groups={"registration"}
	 *     )
	 */
	private $plainPassword;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="date_registered",type="datetime")
	 */
	private $dateRegistered;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="date_edit",type="datetime")
	 */
	private $dateEdit;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="password", type="string", length=64)
	 */
	private $password;

	/**
	 * @ORM\Column(name="is_active", type="boolean")
	 * @Assert\Type(
	 *     type="bool",
	 *     message="The value {{ value }} is not a valid {{ type }}."
	 * )
	 */
	private $isActive;

	/**
	 * @ORM\Column(name="is_not_locked", type="boolean")
	 * @Assert\Type(
	 *     type="bool",
	 *     message="The value {{ value }} is not a valid {{ type }}."
	 * )
	 */
	private $isNotLocked;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="initial_cache", type="decimal", precision=8, scale=2)
	 */
	private $initialCache;

	/**
	 * @var ArrayCollection
	 * @ORM\OneToMany(targetEntity="BlogBundle\Entity\Article",mappedBy="author")
	 * @Assert\Valid()
	 */
	private $articles;

	/**
	 * @var Role
	 * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Role",inversedBy="users",cascade={"remove"})
	 * @ORM\JoinColumn(name="roleId",referencedColumnName="id")
	 *
	 * @Assert\Valid()
	 */
	private $roles;

	/**
	 * @ORM\OneToMany(targetEntity="ShopBundle\Entity\Order",mappedBy="user",cascade={"persist","remove"})
	 * @Assert\Valid()
	 */
	private $orders;


	/**
	 * @ORM\OneToMany(targetEntity="ShopBundle\Entity\ProductUsers",mappedBy="user",cascade={"remove"})
	 * @Assert\Valid()
	 */
	private $productToUsers;

	/**
	 * @var
	 *
	 * @ORM\OneToOne(targetEntity="AppBundle\Entity\UserAddress", inversedBy="user", cascade={"persist","remove"})
	 * @ORM\JoinColumn(name="address_id",referencedColumnName="id")
	 * @Assert\Valid()
	 */
	private $address;

	public function __construct() {
		$this->dateRegistered = new \DateTime('now');
		$this->dateEdit = new \DateTime('now');
		$this->initialCache   = 5000;
		$this->isNotLocked    = true;
		$this->isActive       = false;
		$this->articles       = new ArrayCollection();
		$this->orders         = new ArrayCollection();
		$this->productToUsers = new ArrayCollection();
	}


	/**
	 * @return ArrayCollection
	 */
	public function getArticles() {
		return $this->articles;
	}

	/**
	 * @return mixed
	 */
	public function getPlainPassword() {
		return $this->plainPassword;
	}

	/**
	 * @param mixed $plainPassword
	 */
	public function setPlainPassword( $plainPassword ) {
		$this->plainPassword = $plainPassword;
	}

	/**
	 * @return int|null
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Get username
	 *
	 * @return string
	 */
	public function getUsername() {
		return $this->username;
	}

	/**
	 * Set username
	 *
	 * @param string $username
	 *
	 * @return User
	 */
	public function setUsername( $username ) {
		$this->username = $username;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getFirstName() {
		return $this->firstName;
	}

	/**
	 * @param string $firstName
	 */
	public function setFirstName( $firstName ) {
		$this->firstName = $firstName;
	}

	/**
	 * @return string
	 */
	public function getLastName() {
		return $this->lastName;
	}

	/**
	 * @param string $lastName
	 */
	public function setLastName( $lastName ) {
		$this->lastName = $lastName;
	}

	/**
	 * Get email
	 *
	 * @return string
	 */
	public function getEmail() {
		return $this->email;
	}

	/**
	 * Set email
	 *
	 * @param string $email
	 *
	 * @return User
	 */
	public function setEmail( $email ) {
		$this->email = $email;

		return $this;
	}

	/**
	 * Get password
	 *
	 * @return string
	 */
	public function getPassword() {
		return $this->password;
	}

	/**
	 * Set password
	 *
	 * @param string $password
	 *
	 * @return User
	 */
	public function setPassword( $password ) {
		$this->password = $password;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getOldPassword(): ?string {
		return $this->oldPassword;
	}

	/**
	 * @param string $oldPassword
	 */
	public function setOldPassword( string $oldPassword ): void {
		$this->oldPassword = $oldPassword;
	}

	/**
	 * @return float
	 */
	public function getInitialCache() {
		return $this->initialCache;
	}

	/**
	 * @param float $initialCache
	 */
	public function setInitialCache( $initialCache ) {
		$this->initialCache = $initialCache;
	}

	/**
	 * @return int
	 */
	public function getRoleId() {
		return $this->roles->getId();
	}

	/**
	 * @return array
	 */
	public function getRoles() {
		if ( $this->roles !== null ) {
			return [ $this->roles->getName() ];
		}

		return null;
	}

	/**
	 * @param Role $roles
	 */
	public function setRoles( Role $roles ) {
		if ( $this->roles === null ) {
			$this->roles = $roles->getId();
		}
		$this->roles = $roles;
	}

	public function getSalt() {
		return null;
	}

	public function eraseCredentials() {

	}

	public function serialize() {
		return serialize( array(
			$this->id,
			$this->username,
			$this->password,

		) );
	}

	public function unserialize( $serialized ) {
		list (
			$this->id,
			$this->username,
			$this->password,
			// see section on salt below
			// $this->salt
			) = unserialize( $serialized );
	}

	public function isAccountNonExpired() {
		return true;
	}

	public function isAccountNonLocked() {
		return $this->getIsNotLocked();
	}

	/**
	 * @return mixed
	 */
	public function getIsNotLocked() {
		return $this->isNotLocked;
	}

	/**
	 * @param mixed $isNotLocked
	 */
	public function setIsNotLocked( $isNotLocked ) {
		$this->isNotLocked = $isNotLocked;
	}

	public function isCredentialsNonExpired() {
		return true;
	}

	public function isEnabled() {
		return $this->getIsActive();
	}

	/**
	 * @return mixed
	 */
	public function getIsActive() {
		return $this->isActive;
	}

	/**
	 * @param mixed $isActive
	 */
	public function setIsActive( $isActive ) {
		$this->isActive = $isActive;
	}

	/**
	 * Add article
	 *
	 * @param Article $article
	 *
	 * @return User
	 */
	public function addArticle( Article $article ) {
		$this->articles[] = $article;

		return $this;
	}

	/**
	 * Remove article
	 *
	 * @param Article $article
	 */
	public function removeArticle( Article $article ) {
		$this->articles->removeElement( $article );
	}

	/**
	 * Add order
	 *
	 * @param Order $order
	 *
	 * @return User
	 */
	public function addOrder( Order $order ) {
		$this->orders[] = $order;

		return $this;
	}

	/**
	 * Remove order
	 *
	 * @param Order $order
	 */
	public function removeOrder( Order $order ) {
		$this->orders->removeElement( $order );
	}

	/**
	 * Get orders
	 *
	 * @return Collection
	 */
	public function getOrders() {
		return $this->orders;
	}

	/**
	 * Add productToUser
	 *
	 * @param ProductUsers $productToUser
	 *
	 * @return User
	 */
	public function addProductToUser( ProductUsers $productToUser ) {
		$this->productToUsers[] = $productToUser;

		return $this;
	}

	/**
	 * Remove productToUser
	 *
	 * @param ProductUsers $productToUser
	 */
	public function removeProductToUser( ProductUsers $productToUser ) {
		$this->productToUsers->removeElement( $productToUser );
	}

	/**
	 * Get productToUsers
	 *
	 * @return Collection
	 */
	public function getProductToUsers() {
		return $this->productToUsers;
	}

	/**
	 * @return mixed
	 */
	public function getAddress() {
		return $this->address;
	}

	/**
	 * @param mixed $address
	 */
	public function setAddress( $address ) {
		$this->address = $address;
	}

	/**
	 * @return \DateTime
	 */
	public function getDateRegistered(): \DateTime {
		return $this->dateRegistered;
	}

	/**
	 * @param \DateTime $dateRegistered
	 */
	public function setDateRegistered( \DateTime $dateRegistered ): void {
		$this->dateRegistered = $dateRegistered;
	}

	/**
	 * @return \DateTime
	 */
	public function getDateEdit(): \DateTime {
		return $this->dateEdit;
	}

	/**
	 * @param \DateTime $dateEdit
	 */
	public function setDateEdit( \DateTime $dateEdit ): void {
		$this->dateEdit = $dateEdit;
	}

}
