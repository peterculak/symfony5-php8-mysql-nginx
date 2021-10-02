<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\FactRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * @ORM\Entity(repositoryClass=FactRepository::class)
 */
class Fact
{
    /**
     * @ORM\Id
     * @ManyToOne(targetEntity="Security", inversedBy="facts")
     * @JoinColumn(name="security_id", referencedColumnName="id")
     */
    private Security $security;

    /**
     * @ORM\Id
     * @ManyToOne(targetEntity="Attribute", inversedBy="facts")
     * @JoinColumn(name="attribute_id", referencedColumnName="id")
     */
    private Attribute $attribute;

    /**
     * @ORM\Column(type="float")
     */
    private $value;

    public function __construct(Security $security, Attribute $attribute, float $value)
    {
        $this->security = $security;
        $this->attribute = $attribute;
        $this->value = $value;
    }

    public function getValue(): ?float
    {
        return $this->value;
    }

    /**
     * @param float $value
     * @return $this
     */
    public function setValue(float $value): self
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @param Security $security
     * @return $this
     */
    public function setSecurity(Security $security): self
    {
        $this->security = $security;

        return $this;
    }

    /**
     * @param Attribute $attribute
     * @return $this
     */
    public function setAttribute(Attribute $attribute): self
    {
        $this->attribute = $attribute;

        return $this;
    }

    /**
     * @return Attribute
     */
    public function attribute(): Attribute
    {
        return $this->attribute;
    }

    /**
     * @return Security
     */
    public function security(): Security
    {
        return $this->security;
    }
}
