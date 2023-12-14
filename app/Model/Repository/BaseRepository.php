<?php declare(strict_types=1);

namespace Rally\Model\Repository;

use Doctrine\ORM\EntityRepository;
use InvalidArgumentException;
use Nettrine\ORM\EntityManagerDecorator;

abstract class BaseRepository
{

	public function __construct(private readonly EntityManagerDecorator $entityManager)
	{
	}

	public function save(object $entity, bool $flush = true): void
	{
		$this->validateEntity($entity);

		$this->entityManager->persist($entity);

		if ($flush) {
			$this->entityManager->flush();
		}
	}

	public function delete(object $entity, bool $flush = true): void
	{
		$this->validateEntity($entity);

		$this->entityManager->remove($entity);

		if ($flush) {
			$this->entityManager->flush();
		}
	}

	protected function validateEntity(object $entity): void
	{
		$className = $this->getClassName();
		if (!($entity instanceof $className)) {
			throw new InvalidArgumentException('Entity passed to ' . get_class($this) . ' must be instance of ' . $className . ', ' . get_class($entity) . ' given.');
		}
	}

	protected function getRepository(): EntityRepository
	{
		return $this->entityManager->getRepository($this->getClassName());
	}

	abstract protected function getClassName(): string;

}
