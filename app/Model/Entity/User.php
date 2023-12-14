<?php declare(strict_types=1);

namespace Rally\Model\Entity;

use Doctrine\ORM\Mapping as ORM;
use Rally\Model\Entity\Attributes\Identifier;

#[ORM\Entity]
class User
{

	use Identifier;

	#[ORM\Column]
	public string $login;

	#[ORM\Column]
	public string $password;

}
