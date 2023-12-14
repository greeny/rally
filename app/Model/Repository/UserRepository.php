<?php declare(strict_types=1);

namespace Rally\Model\Repository;

use Rally\Model\Entity\User;

class UserRepository extends BaseRepository
{

	public function getById(int $id): ?User
	{
		return $this->getRepository()->find($id);
	}

	public function getByLogin(string $login): ?User
	{
		return $this->getRepository()->findOneBy(['login' => $login]);
	}

	protected function getClassName(): string
	{
		return User::class;
	}

}
