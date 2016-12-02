<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Operation;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

/**
 * Account
 *
 * @ApiResource(attributes={
 *     "normalization_context"={"groups"={"read_account"}},
 *     "denormalization_context"={"groups"={"write_account"}},
 *     "pagination_enabled"=false
 * })
 * @ORM\Table(name="account")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AccountRepository")
 */
class Account
{
    /**
     * @var int
     *
     * @Groups({"read_account", "read_operation"})
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @Groups({"read_account", "write_account", "read_operation"})
     * @ORM\Column(name="name", type="string", length=50)
     */
    private $name;

    /**
     * @var string
     *
     * @Groups({"read_account", "write_account"})
     * @ORM\Column(name="description", type="string", length=250)
     */
    private $description;

    /**
     * @var string
     *
     * @Groups({"read_account", "write_account"})
     * @ORM\Column(name="number", type="string", length=50)
     */
    private $number;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $user;

    /**
     * @var array
     *
     * @ORM\OneToMany(targetEntity="Operation", mappedBy="account")
     */
    private $operations;

    /**
     * @var int
     *
     * @Groups({"read_account"})
     */
    private $balance;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Account
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Account
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set number
     *
     * @param string $number
     *
     * @return Account
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set user
     *
     * @param User $user
     *
     * @return Account
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set operations
     *
     * @param array $operations
     *
     * @return Account
     */
    public function setOperations($operations)
    {
        $this->operations = $operations;

        return $this;
    }

    /**
     * Get operations
     *
     * @return array
     */
    public function getOperations()
    {
        return $this->operations;
    }

    /**
     * Set balance
     *
     * @param int $balance
     *
     * @return Account
     */
    public function setBalance($balance)
    {
        $this->balance = $balance;

        return $this;
    }

    /**
     * Get balance
     *
     * @return int
     */
    public function getBalance()
    {
        return $this->balance;
    }
}

