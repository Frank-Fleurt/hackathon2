<?php

namespace App\Entity;

use App\Repository\LimitdateRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LimitdateRepository::class)]
class Limitdate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }
}
