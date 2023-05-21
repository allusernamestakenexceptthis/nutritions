<?php
declare(strict_types=1);

namespace Gomilkyway\Nutrition\Utils;

/**
 * Utility for unit conversion
 *
 * @copyright  Copyright (C) Gomilkyway (https://gomilkyway.com)
 * @package    Gomilkyway\Nutrition\Models\Nutritions
 * @author     Adari ARi
 * @version    0.1.0
 * @license    MIT License (https://opensource.org/licenses/mit-license.php)
 */

use Gomilkyway\Nutrition\Errors\Exception;

class UnitConverter
{

    private array $units = [
        ['weight'] => [
            "mg" => 1,
            "g" => 1000,
        ],
        ['energy'] => [
            "cal" => 1,
            "kcal" => 1000,
        ],
    ];


    public static function convert(mixed $value, string $from, string $to): mixed /*throws exception*/
    {
        foreach (self::$units as $units) {
            if (isset($units[$from]) && isset($units[$to])) {
                return $value * $units[$from] / $units[$to];
            }
        }

        throw new Exception("Invalid units given");
    }


    public static function validateUnit($unit, $type): bool
    {
        return isset(self::$units[$type][$unit]);
    }

    public static function getValueWithUnit(mixed $value, string $unit = ""): array
    {
        // if not string, then we use default
        if (is_string($value)) {
            //remove spaces and convert to lowercase
            $value = trim(strtolower($value));

            //separate numerical value from unit
            //TODO: add support for locale and decimal separator
            if (preg_match("/^([0-9.]+)([a-zA-Z]*)$/", $value, $matches)) {
                $value = $matches[1];

                if (!empty($matches[1])) {
                    $unit = $matches[2];
                }
            } else {
                throw new Exception("Invalid value/unit given");
            }
        }

        if (!$unit) {
            throw new Exception("Unit not given");
        }

        return [$value, $unit];
    }

}
