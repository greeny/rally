<?php declare(strict_types=1);

namespace Rally\Model\Repository;

use Rally\Model\Entity\Event;

class EventRepository extends BaseRepository
{

	/** @return Event[] */
	public function getAll(): array
	{
		return $this->getRepository()->findBy([], ['start' => 'ASC']);
	}

	public function getById(int $id): ?Event
	{
		return $this->getRepository()->find($id);
	}

	public function getByRemoteId(string $remoteId): ?Event
	{
		return $this->getRepository()->findOneBy(['remoteId' => $remoteId]);
	}

	protected function getClassName(): string
	{
		return Event::class;
	}

}
