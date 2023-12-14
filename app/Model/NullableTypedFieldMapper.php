<?php declare(strict_types=1);

namespace Rally\Model;

use DateInterval;
use DateTime;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\TypedFieldMapper;
use ReflectionEnum;
use ReflectionNamedType;
use ReflectionProperty;

// unfortunately, DefaultTypedFieldMapper is final, so we have to copy it to extend
class NullableTypedFieldMapper implements TypedFieldMapper
{

	private array $typedFieldMappings;

	private const DEFAULT_TYPED_FIELD_MAPPINGS = [
		DateInterval::class => Types::DATEINTERVAL,
		DateTime::class => Types::DATETIME_MUTABLE,
		DateTimeImmutable::class => Types::DATETIME_IMMUTABLE,
		'array' => Types::JSON,
		'bool' => Types::BOOLEAN,
		'float' => Types::FLOAT,
		'int' => Types::INTEGER,
		'string' => Types::STRING,
	];

	public function __construct(array $typedFieldMappings = [])
	{
		$this->typedFieldMappings = array_merge(self::DEFAULT_TYPED_FIELD_MAPPINGS, $typedFieldMappings);
	}

	public function validateAndComplete(array $mapping, ReflectionProperty $field): array
	{
		$type = $field->getType();

		if (!isset($mapping['type']) && ($type instanceof ReflectionNamedType)) {
			if (PHP_VERSION_ID >= 80100 && !$type->isBuiltin() && enum_exists($type->getName())) {
				$mapping['enumType'] = $type->getName();

				$reflection = new ReflectionEnum($type->getName());
				$type = $reflection->getBackingType();
			}

			if (isset($this->typedFieldMappings[$type->getName()])) {
				$mapping['type'] = $this->typedFieldMappings[$type->getName()];
			}
		}

		$mapping['nullable'] = $type->allowsNull();

		return $mapping;
	}

}
