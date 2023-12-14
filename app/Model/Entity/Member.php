<?php declare(strict_types=1);

namespace Rally\Model\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Rally\Model\Entity\Attributes\Identifier;

#[ORM\Entity]
class Member
{

	use Identifier;

	#[ORM\Column]
	public string $name;

	#[ORM\Column]
	public string $surname;

	#[ORM\ManyToMany(targetEntity: Role::class)]
	#[ORM\JoinColumn(nullable: false)]
	public Collection $roles;

	public function __construct()
	{
		$this->roles = new ArrayCollection;
	}

}
