<?php declare(strict_types=1);

namespace Rally\Model\Repository;

use Rally\Model\Entity\Language;

class LanguageRepository extends BaseRepository
{

	/** @return Language[] */
	public function getAll(): array
	{
		return $this->getRepository()->findAll();
	}

	protected function getClassName(): string
	{
		return Language::class;
	}

}
