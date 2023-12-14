<?php declare(strict_types=1);

namespace Rally\Model\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Rally\Model\Entity\Attributes\Identifier;
use Rally\Model\Enum\RoleType;

#[ORM\Entity]
class Role
{

	use Identifier;

	#[ORM\Column(unique: true)]
	public RoleType $type;

	#[ORM\Column]
	public int $min;

	#[ORM\Column]
	public int $max;

	#[ORM\OneToMany(mappedBy: 'role', targetEntity: RoleTranslation::class, cascade: ['persist'])]
	public Collection $translations;

	public function __construct()
	{
		$this->translations = new ArrayCollection;
	}

	public function translation(string $lang): ?RoleTranslation
	{
		/** @var RoleTranslation $translation */
		foreach ($this->translations as $translation) {
			if ($translation->language->code === $lang) {
				return $translation;
			}
		}
		return null;
	}

}
