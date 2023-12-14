<?php declare(strict_types=1);

namespace Rally\Model\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Rally\Model\Entity\Attributes\Identifier;

#[ORM\Entity]
class Event
{

	use Identifier;

	#[ORM\Column]
	public string $remoteId;

	#[ORM\Column]
	public string $name;

	#[ORM\Column]
	public DateTimeImmutable $start;

	#[ORM\Column]
	public DateTimeImmutable $end;

}
