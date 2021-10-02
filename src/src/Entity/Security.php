<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\SecurityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * @ORM\Entity(repositoryClass=SecurityRepository::class)
 */
class Security
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @OneToMany(targetEntity="Fact", mappedBy="security")
     */
    private $facts;

    /**
     * @ORM\Column(type="string", length=3)
     */
    private $symbol;

    public function __construct(int $id, string $symbol)
    {
        $this->id = $id;
        $this->symbol = $symbol;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSymbol(): ?string
    {
        return $this->symbol;
    }

    public function setSymbol(string $symbol): self
    {
        $this->symbol = $symbol;

        return $this;
    }

    public function facts(): ArrayCollection
    {
        return $this->facts;
    }
}
