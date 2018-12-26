<?php

namespace AppBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;
use AppBundle\Action\TagBudgetPut;
use AppBundle\Action\TagsBudgetGet;

/**
 * Tag
 *
 * @ApiResource(attributes={
 *     "normalization_context"={"groups"={"read_tag"}},
 *     "denormalization_context"={"groups"={"write_tag"}},
 *     "pagination_enabled"=false
 * }, itemOperations={
 *     "get"={"method"="GET"},
 *     "put"={"method"="PUT"},
 *     "delete"={"method"="DELETE"},
 *     "put_budget"={
 *         "method"="PUT",
 *         "path"="/tags/{id}/budget",
 *         "controller"=TagBudgetPut::class,
 *         "normalization_context"={"groups"={"read_tag_budget"}},
 *         "denormalization_context"={"groups"={"write_tag_budget"}}
 *     }
 * }, collectionOperations={
 *     "get"={"method"="GET"},
 *     "post"={"method"="POST"},
 *     "get_budget"={
 *         "method"="GET",
 *         "path"="/tags/budget",
 *         "controller"=TagsBudgetGet::class,
 *         "normalization_context"={"groups"={"read_tags_budget"}},
 *         "denormalization_context"={"groups"={"write_tags_budget"}}
 *     }
 * })
 * @ORM\Table(name="tag")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TagRepository")
 */
class Tag
{
    /**
     * @var int
     *
     * @Groups({"read_tag", "read_operation", "read_tags_budget"})
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @Groups({"read_tag", "write_tag", "read_operation", "read_tags_budget"})
     * @ORM\Column(name="name", type="string", length=50)
     */
    private $name;

    /**
     * @var string
     *
     * @Groups({"read_tag", "write_tag"})
     * @ORM\Column(name="description", type="string", length=250)
     */
    private $description;

    /**
     * @var string
     *
     * @Groups({"read_tag", "write_tag", "read_operation", "read_tags_budget"})
     * @ORM\Column(name="icon", type="string", length=20, nullable=true)
     */
    private $icon;

    /**
     * @var string
     *
     * @Groups({"read_tag", "write_tag", "read_operation", "read_tags_budget"})
     * @ORM\Column(name="color", type="string", length=20, nullable=true)
     */
    private $color;

    /**
     * @var boolean
     *
     * @Groups({"read_tag", "write_tag"})
     * @ORM\Column(name="disabled", type="boolean")
     */
    private $disabled = false;

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
     * @ORM\OneToMany(targetEntity="Operation", mappedBy="tag")
     */
    private $operations;

    /**
     * @var TagBudget[]
     *
     * @ORM\OneToMany(targetEntity="TagBudget", mappedBy="tag", cascade={"persist"})
     */
    private $budgets = [];

    /**
     * @var int|null
     *
     * @Groups({"write_tag_budget", "read_tags_budget"})
     */
    private $budgetAmount;

    /**
     * @var int
     *
     * @Groups({"write_tag_budget"})
     */
    private $budgetYear;

    /**
     * @var int
     *
     * @Groups({"read_tags_budget"})
     */
    private $totalAmount;

    /**
     * @var int|null
     *
     * @Groups({"read_tags_budget"})
     */
    private $gap;


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
     * @return Tag
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
     * @return Tag
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
     * Set icon
     *
     * @param string $icon
     *
     * @return Tag
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Get icon
     *
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Set color
     *
     * @param string $color
     *
     * @return Tag
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color
     *
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set disabled
     *
     * @param boolean $disabled
     *
     * @return Tag
     */
    public function setDisabled($disabled)
    {
        $this->disabled = $disabled;

        return $this;
    }

    /**
     * Get disabled
     *
     * @return boolean
     */
    public function isDisabled()
    {
        return $this->disabled;
    }

    /**
     * Set user
     *
     * @param User $user
     *
     * @return Tag
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
     * @return Tag
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
     * Set budgets
     *
     * @param TagBudget[] $budgets
     *
     * @return Tag
     */
    public function setBudgets($budgets)
    {
        $this->budgets = $budgets;

        return $this;
    }

    /**
     * Get budgets
     *
     * @return TagBudget[]
     */
    public function getBudgets()
    {
        return $this->budgets;
    }

    /**
     * @param TagBudget $budget
     * @return Tag
     */
    public function addBudget(TagBudget $budget): Tag
    {
        $budget->setTag($this);
        $this->budgets[] = $budget;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getBudgetAmount()
    {
        return $this->budgetAmount;
    }

    /**
     * @param int|null $budgetAmount
     */
    public function setBudgetAmount($budgetAmount)
    {
        $this->budgetAmount = $budgetAmount;
    }

    /**
     * @return int|null
     */
    public function getBudgetYear(): int
    {
        return $this->budgetYear;
    }

    /**
     * @param int $budgetYear
     */
    public function setBudgetYear(int $budgetYear)
    {
        $this->budgetYear = $budgetYear;
    }

    /**
     * @return int
     */
    public function getTotalAmount(): int
    {
        return $this->totalAmount;
    }

    /**
     * @param int $totalAmount
     */
    public function setTotalAmount(int $totalAmount)
    {
        $this->totalAmount = $totalAmount;
    }

    /**
     * @return int|null
     */
    public function getGap()
    {
        return $this->gap;
    }

    /**
     * @param int|null $gap
     */
    public function setGap($gap)
    {
        $this->gap = $gap;
    }
}

