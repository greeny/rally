<?php declare(strict_types=1);

namespace Rally\Model\Entity;

use Doctrine\ORM\Mapping as ORM;
use Rally\Model\Entity\Attributes\Identifier;

#[ORM\Entity]
class Language
{

	use Identifier;

	#[ORM\Column]
	public string $code;

	#[ORM\Column]
	public string $name;

}
