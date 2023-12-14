<?php declare(strict_types=1);

namespace Rally\Model\Entity\Attributes;

use Doctrine\ORM\Mapping as ORM;

trait Identifier
{

	#[ORM\Column]
	#[ORM\GeneratedValue]
	#[ORM\Id]
	public int $id;

	public function __clone(): void
	{
		unset($this->id);
	}

}
