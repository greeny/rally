<?php declare(strict_types=1);

namespace Rally\Model\Entity;

use Doctrine\ORM\Mapping as ORM;
use Rally\Model\Entity\Attributes\Identifier;

#[ORM\Entity]
class RoleTranslation
{

	use Identifier;

	#[ORM\ManyToOne(cascade: ['persist'])]
	#[ORM\JoinColumn(nullable: false)]
	public Language $language;

	#[ORM\ManyToOne(cascade: ['persist'])]
	#[ORM\JoinColumn(nullable: false)]
	public Role $role;

	#[ORM\Column]
	public string $name;

}
