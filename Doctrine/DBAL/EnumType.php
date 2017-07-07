<?php

namespace KRG\ShippingBundle\Doctrine\DBAL;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

abstract class EnumType extends Type
{
    public static $values = array();

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        $values = array_map(
            function ($val) {
                return "'".$val."'";
            }, array_keys(static::$values)
        );
        sort($values);

        return "ENUM(".implode(", ", $values).") COMMENT '(DC2Type:".$this->getName().")'";
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (null === $value) {
            return null;
        }
        if (!isset(static::$values[$value])) {
            throw new \InvalidArgumentException("Invalid '".$this->getName()."' key.");
        }

        return static::$values[$value];
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (null !== $value && !isset(static::$values[$value])) {
            throw new \InvalidArgumentException("Invalid '".$this->getName()."' key.");
        }

        return $value;
    }

    public static function getValue($value)
    {
        if (!isset(static::$values[$value])) {
            throw new \InvalidArgumentException("Invalid '".$value."' key.");
        }

        return static::$values[$value];
    }

    public static function getKey($value)
    {
        if (($key = array_search($value, static::$values)) !== false) {
            return $key;
        }
        throw new \InvalidArgumentException("Invalid '".$value."' value.");
    }

    public static function getChoices(array $values = null)
    {
        $values = $values !== null ? $values : static::$values;

        return array_map(
            function ($choice) {
                return ucwords(preg_replace('/[-_.]/', ' ', $choice));
            },
            array_combine($values, $values)
        );
    }
}
