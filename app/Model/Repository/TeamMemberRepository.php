<?php declare(strict_types=1);

namespace Rally\Model\Repository;

use Rally\Model\Entity\TeamMember;

class TeamMemberRepository extends BaseRepository
{

	protected function getClassName(): string
	{
		return TeamMember::class;
	}

}
