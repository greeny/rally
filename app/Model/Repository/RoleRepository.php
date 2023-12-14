<?php declare(strict_types=1);

namespace Rally\Model\Repository;

use Doctrine\ORM\Query\Expr\Join;
use Rally\Model\Entity\Language;
use Rally\Model\Entity\Role;
use Rally\Model\Entity\RoleTranslation;

class RoleRepository extends BaseRepository
{

	/** @return Role[] */
	public function getAll(): array
	{
		return $this->getRepository()->findAll();
	}

	public function getTypePairs(string $language): array
	{
		$result = [];
		$rows = $this->getRepository()->createQueryBuilder('r')
			->select('r.id AS id, rt.name AS name')
			->join(RoleTranslation::class, 'rt', Join::WITH, 'r = rt.role')
			->join(Language::class, 'l', Join::WITH, 'rt.language = l')
			->andWhere('l.code = :language')
			->setParameter('language', $language)
			->getQuery()
			->getArrayResult();

		foreach ($rows as $row) {
			$result[$row['id']] = $row['name'];
		}

		return $result;
	}

	/**
	 * @param int[] $roles
	 * @return Role[]
	 */
	public function getByIds(array $roles): array
	{
		return count($roles) ? $this->getRepository()->findBy(['id' => $roles]) : [];
	}

	protected function getClassName(): string
	{
		return Role::class;
	}

}
