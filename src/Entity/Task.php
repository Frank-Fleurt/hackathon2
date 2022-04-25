<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'datetime')]
    private $date;

    #[ORM\ManyToOne(targetEntity: Type::class, inversedBy: 'tasks')]
    #[ORM\JoinColumn(nullable: false)]
    private $type;

    #[ORM\ManyToOne(targetEntity: Clients::class, inversedBy: 'tasks')]
    #[ORM\JoinColumn(nullable: false)]
    private $client;

    #[ORM\Column(type: 'string', length: 7)]
    private $background_Color;

    #[ORM\Column(type: 'boolean')]
    private $all_day;

    #[ORM\Column(type: 'string', length: 7)]
    private $border_color;

    #[ORM\Column(type: 'string', length: 7)]
    private $text_color;

    #[ORM\Column(type: 'datetime')]
    private $limitDate;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setType(?Type  $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getClient(): ?Clients
    {
        return $this->client;
    }

    public function setClient(?Clients $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getBackgroundColor(): ?string
    {
        return $this->background_Color;
    }

    public function setBackgroundColor(string $background_Color): self
    {
        $this->background_Color = $background_Color;

        return $this;
    }

    public function getAllDay(): ?bool
    {
        return $this->all_day;
    }

    public function setAllDay(bool $all_day): self
    {
        $this->all_day = $all_day;

        return $this;
    }

    public function getBorderColor(): ?string
    {
        return $this->border_color;
    }

    public function setBorderColor(string $border_color): self
    {
        $this->border_color = $border_color;

        return $this;
    }

    public function getTextColor(): ?string
    {
        return $this->text_color;
    }

    public function setTextColor(string $text_color): self
    {
        $this->text_color = $text_color;

        return $this;
    }

    public function getLimitDate(): ?\DateTimeInterface
    {
        return $this->limitDate;
    }

    public function setLimitDate(\DateTimeInterface $limitDate): self
    {
        $this->limitDate = $limitDate;

        return $this;
    }


}
