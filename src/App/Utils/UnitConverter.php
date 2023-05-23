<?php
declare(strict_types=1);

namespace App\Utils;

/**
 * Utility for unit conversion
 *
 * @copyright  Copyright (C) Gomilkyway (https://gomilkyway.com)
 * @package    App\Models\Nutritions
 * @author     Adari ARi
 * @version    0.1.0
 * @license    MIT License (https://opensource.org/licenses/mit-license.php)
 */

use App\Errors\Exception;

class UnitConverter
{

    // hold list of valid units and their values among each others
    private static $units;


    /**
     * get valid units and their values, set them if not set
     *
     * @return array
     */
    public static function getUnits() : array
    {
        if (!isset(self::$units)) {
            self::$units = [
                'weight' => [
                    "mg" => 1,
                    "g" => 1000,
                    "kg" => 1000000,
                ],
                'energy' => [
                    "cal" => 1,
                    "kcal" => 1000,
                ],
            ];
        }

        return self::$units;
    }

    /**
     * convert value from one unit to another
     *
     * @param mixed $value      value to convert
     * @param string $from      unit to convert from
     * @param string $to        unit to convert to
     * @return mixed            converted value
     * @throws Exception        if invalid units given
     */
    public static function convert(mixed $value, string $from, string $to): mixed /*throws exception*/
    {
        if ($from == $to) {
            return $value;
        }
        foreach (self::getUnits() as $units) {
            if (isset($units[$from]) && isset($units[$to])) {
                return (float)bcmul((string)$value, bcdiv((string)$units[$from], (string)$units[$to], 10), 10);
            }
        }

        throw new Exception("Invalid units given");
    }


    /**
     * validate unit
     * check if given unit is valid for given type
     *
     * @param [type] $unit    unit to validate
     * @param [type] $type    type of unit to validate
     * @return boolean        true if valid, false otherwise
     */
    public static function validateUnit($unit, $type): bool
    {
        $units = self::getUnits();
        return isset($units[$type][$unit]);
    }

    /**
     * get value with unit
     * separate numerical value from unit e.g. 100g => [100, g]
     *
     * @param mixed $value      value to separate
     * @param string $unit      unit to separate
     * @return array            separated value and unit
     * @throws Exception        if invalid value/unit given
     */
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
            throw new Exception("Unit not given in line: " . __LINE__);
        }

        return [floatval($value), $unit];
    }

}
