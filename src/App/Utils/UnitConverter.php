<?php

/**
 * Utility for unit conversion
 * 単位変換のためのユーティリティ
 *
 * @copyright  Copyright (C) Gomilkyway (https://gomilkyway.com)
 * @package    App\Models\Nutritions
 * @author     Adari ARi
 * @version    0.1.0
 * @license    MIT License (https://opensource.org/licenses/mit-license.php)
 */

declare(strict_types=1);

namespace App\Utils;

use App\Errors\Exception;

class UnitConverter
{
    // hold list of valid units and their values among each others
    // 有効な単位とその値のリストを保持する
    private static $units;


    /**
     * get valid units and their values, set them if not set
     * 有効な単位とその値を取得し、設定されていない場合は設定する
     *
     * @return array list of valid units and their values リストの有効な単位とその値
     */
    public static function getUnits(): array
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
     * 1つの単位から別の単位に値を変換する
     *
     * @param mixed $value      value to convert 変換する値
     * @param string $from      unit to convert from 変換元の単位
     * @param string $to        unit to convert to 変換先の単位
     * @return mixed            converted value 変換された値
     * @throws Exception        if invalid units given 無効な単位が指定された場合
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

        throw new Exception("Invalid units given");// 無効な単位が指定されました
    }


    /**
     * validate unit
     * check if given unit is valid for given type
     * 単位を検証する
     * 指定されたタイプに対して指定された単位が有効かどうかを確認します
     *
     * @param [type] $unit    unit to validate 単位を検証する
     * @param [type] $type    type of unit to validate 検証する単位のタイプ
     * @return boolean        true if valid, false otherwise 有効な場合はtrue、それ以外はfalse
     */
    public static function validateUnit($unit, $type): bool
    {
        $units = self::getUnits();
        return isset($units[$type][$unit]);
    }

    /**
     * get value with unit
     * separate numerical value from unit e.g. 100g => [100, g]
     * 値と単位を取得する
     * 数値の値を単位から分離する 例：100g => [100, g]
     *
     * @param mixed $value      value to separate　分離する値
     * @param string $unit      unit to separate　分離する単位
     * @return array            separated value and unit　分離された値と単位
     * @throws Exception        if invalid value/unit given　無効な値/単位が指定された場合
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
                throw new Exception("Invalid value/unit given");// 無効な値/単位が指定されました
            }
        }

        if (!$unit) {
            throw new Exception("Unit not given in line: " . __LINE__);// 単位が指定されていません
        }

        return [floatval($value), $unit];
    }
}
