<?php

declare(strict_types=1);

namespace Venomousboy\DoctrineEnumerableType;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use LitGroup\Enumerable\Enumerable;

abstract class EnumerableType extends Type
{
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        return $platform->getVarcharTypeDeclarationSQL(['length' => 63]);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (null === $value) {
            return null;
        }
        /** @var Enumerable $class */
        $class = $this->getEnumClass();

        return $class::getValueOf($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (null === $value) {
            return null;
        }
        if (!$value instanceof Enumerable) {
            throw new \InvalidArgumentException('Value must be ' . Enumerable::class . ' type');
        }
        return $value->getRawValue();
    }

    abstract protected function getEnumClass(): string;
}
