<?php declare(strict_types=1);

namespace Tests;

final class TestCodes
{
    const CODES_ID = TestCodes::class;

    const ERROR = 0;
    const SUCCESS = 1;
    const WARNING = 2;

    static function Convert_To(int $code,string $type="string")
    {
        switch($type)
        {
            case "string":
                return array_search($code,(new \ReflectionClass(static::class))->getConstants(),true);
            default:
            throw new \InvalidArgumentException("Argument for specifying conversion type was incorrect");
        }
    }
}
