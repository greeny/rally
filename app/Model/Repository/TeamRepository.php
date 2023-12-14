<?php declare(strict_types=1);

namespace Rally\Model\Repository;

use Rally\Model\Entity\Team;

class TeamRepository extends BaseRepository
{

	/** @return Team[] */
	public function getAll(): array
	{
		return $this->getRepository()->findAll();
	}

	public function getById(int $id): ?Team
	{
		return $this->getRepository()->find($id);
	}

	protected function getClassName(): string
	{
		return Team::class;
	}

}
