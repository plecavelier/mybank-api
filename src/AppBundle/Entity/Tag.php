<?php

namespace AppBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

/**
 * Tag
 *
 * @ApiResource(attributes={
 *     "normalization_context"={"groups"={"read_tag"}},
 *     "denormalization_context"={"groups"={"write_tag"}},
 *     "pagination_enabled"=false
 * })
 * @ORM\Table(name="tag")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TagRepository")
 */
class Tag
{
    /**
     * @var int
     *
     * @Groups({"read_tag", "read_operation"})
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @Groups({"read_tag", "write_tag", "read_operation"})
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
     * @Groups({"read_tag", "write_tag", "read_operation"})
     * @ORM\Column(name="icon", type="string", length=20, nullable=true)
     */
    private $icon;

    /**
     * @var string
     *
     * @Groups({"read_tag", "write_tag", "read_operation"})
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
}

