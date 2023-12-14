<?php declare(strict_types=1);

namespace Rally\Model\Repository;

use Rally\Model\Entity\Member;
use Rally\Model\Entity\Role;

class MemberRepository extends BaseRepository
{

	/** @return Member[] */
	public function getAll(): array
	{
		return $this->getRepository()->findAll();
	}

	public function getById(int $id): ?Member
	{
		return $this->getRepository()->find($id);
	}

	/**
	 * @param int[] $ids
	 * @return Member[]
	 */
	public function getByIds(array $ids): array
	{
		return $this->getRepository()->findBy(['id' => $ids]);
	}

	public function getPairsByRole(Role $role): array
	{
		$result = [];
		$rows = $this->getRepository()->createQueryBuilder('m')
			->select('m.id AS id, m.name AS name, m.surname AS surname')
			->join('m.roles', 'r')
			->andWhere('r = :role')
			->setParameter('role', $role)
			->getQuery()
			->getArrayResult();

		foreach ($rows as $row) {
			$result[$row['id']] = $row['name'] . ' ' . $row['surname'];
		}

		return $result;
	}

	protected function getClassName(): string
	{
		return Member::class;
	}

}
