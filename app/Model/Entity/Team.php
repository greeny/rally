<?php declare(strict_types=1);

namespace Rally\Model\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Rally\Model\Entity\Attributes\Identifier;

#[ORM\Entity]
class Team
{

	use Identifier;

	#[ORM\Column]
	public string $name;

	#[ORM\OneToMany(mappedBy: 'team', targetEntity: TeamMember::class, cascade: ['persist'])]
	public Collection $members;

	public function __construct()
	{
		$this->members = new ArrayCollection;
	}

	/** @return Member[] */
	public function getMembersByRole(Role $role): array
	{
		return $this->members->filter(function (TeamMember $member) use ($role) {
			return $member->role === $role;
		})->map(function (TeamMember $teamMember) {
			return $teamMember->member;
		})->toArray();
	}

}
