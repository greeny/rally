<?php declare(strict_types=1);

namespace Rally\Model\Entity;

use Doctrine\ORM\Mapping as ORM;
use Rally\Model\Entity\Attributes\Identifier;

#[ORM\Entity]
class TeamMember
{

	use Identifier;

	#[ORM\ManyToOne(cascade: ['persist'])]
	#[ORM\JoinColumn(nullable: false)]
	public Team $team;

	#[ORM\ManyToOne(cascade: ['persist'])]
	#[ORM\JoinColumn(nullable: false)]
	public Member $member;

	#[ORM\ManyToOne(cascade: ['persist'])]
	#[ORM\JoinColumn(nullable: false)]
	public Role $role;

}
