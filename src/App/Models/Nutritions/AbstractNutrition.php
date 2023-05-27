<?php

/**
 * Abstract class acts as parent that holds common methods, input and output
 * アブストラクトクラスは、テスト用の共通メソッド、入力、出力を保持する親クラス
 *
 * @copyright  Copyright (C) Gomilkyway (https://gomilkyway.com)
 * @package    App\Models\Nutritions
 * @author     Adari ARi
 * @version    0.1.0
 * @license    MIT License (https://opensource.org/licenses/mit-license.php)
 */

declare(strict_types=1);

namespace App\Models\Nutritions;

use App\Errors\Exception;
use App\Utils\UnitConverter;

abstract class AbstractNutrition implements InterfaceNutrition
{
    //calories per mg
    //mgごとのカロリー
    protected int $caloriesPerMg = 0;

    //nutrition name such as Carbs
    //栄養素名（炭水化物など）
    protected string $name = "";

    //nutrition code such as C
    //栄養素コード（Cなど）
    protected string $code = "";

    //nutrition description
    //栄養素の説明
    protected string $description = "";

    //nutrition weight
    //栄養素の重さ
    protected array $extras = [];


    /**
     * AbstractNutrition constructor.
     * AbstractNutrition コンストラクタ
     *
     * @param string $name          nutrition name         栄養素名
     * @param mixed $energy         nutrition energy       栄養素エネルギー
     * @param string $code          nutrition code         栄養素コード
     * @param string $eneryUnit     nutrition energy unit  栄養素エネルギー単位
     * @param string $weightUnit    nutrition weight unit  栄養素重量単位
     */
    public function __construct(
        string $name,
        mixed $energy,
        string $code = "",
        string $eneryUnit = "kcal",
        string $weightUnit = "g"
    ) {
        if (!$code) {
            $code = $name;
        }

        $this->name = $name;
        $this->code = $code;
        $this->setEnergy($energy, $eneryUnit, $weightUnit);
    }

    /**
     * Set Calories per mg
     * カロリーを設定する
     *
     * @param integer $calories   カロリー
     * @return void
     */
    public function setCaloriesPerMg(int $calories): void
    {
        $this->caloriesPerMg = $calories;
    }

    /**
     * Get Calories per mg
     * カロリーを取得する
     *
     * @return integer
     */
    public function getCaloriesPerMg(): int
    {
        return $this->caloriesPerMg;
    }

    /**
     * get nutrition energy in desired unit per weight unit
     * 重量単位ごとの所望の単位の栄養エネルギーを取得する
     *
     * @param float $weight         weight value e.g. 2.5          重量値
     * @param string $caloriesIn    calories unit default: kcal    カロリー単位
     * @param string $per           weight unit default: g         重量単位
     * @return void
     * @throws Exception            if units are invalid           単位が無効な場合
     */
    public function getEnergy(float $weight, string $caloriesIn = "kcal", string $per = "g"): mixed
    {
        //throws exception if units are invalid
        //単位が無効な場合は例外をスローする
        $this->validateAllInputs($caloriesIn, $per);

        $caloriesPerMg = $this->getCaloriesPerMg();
        $weightInMg = $this->converter($weight, $per, "mg");

        $energy = bcmul((string)$caloriesPerMg, (string)$weightInMg);
        $res = (float) $this->converter($energy, "cal", $caloriesIn);
        if ($caloriesIn == "kcal") {
            $res = round($res);
        }
        return $res;
    }

    /**
     * set nutrition energy in desired unit per weight unit
     * 重量単位ごとの所望の単位の栄養エネルギーを設定する
     *
     * @param integer $calories     calories value e.g. 5        カロリー値
     * @param string $in            calories unit default: kcal  カロリー単位
     * @param string $per           weight unit default: g       重量単位
     * @return mixed energy in desired unit per weight unit      所望の単位ごとのエネルギー
     */
    public function setEnergy(int $calories, string $in = "kcal", string $per = "g"): mixed
    {
        $calories = $this->converter($calories, $in, "cal");

        $mg = $this->converter(1, $per, "mg");

        $this->setCaloriesPerMg(intval($calories / $mg));

        return $this->getCaloriesPerMg();
    }

    /**
     * convert nutrition units using UnitConverter, round to 2 decimal places if unit is g
     * UnitConverterを使用して栄養単位を変換します。単位がgの場合は小数点以下2桁に丸めます。
     *
     * @param mixed $value      value to convert      変換する値
     * @param string $from      unit to convert from  変換元の単位
     * @param string $to        unit to convert to    変換先の単位
     * @return mixed            converted value       変換された値
     */
    public function converter(mixed $value, string $from, string $to): mixed /*throws exception*/
    {
        //round to 2 decimal places
        //小数点以下2桁に丸める
        if ($to == "g") {
            $value = round((float)$value, 2);
        }

        return UnitConverter::convert($value, $from, $to);
    }

    /**
     * Get nutrition name
     * 栄養素名を取得する
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set nutrition name
     * 栄養素名を設定する
     *
     * @param string $name    nutrition name      栄養素名
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Get nutrition code
     * 栄養素コードを取得する
     *
     * @return string nutrition code      栄養素コード
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * Get nutrition description
     * 栄養素の説明を取得する
     *
     * @return string nutrition description      栄養素の説明
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Get nutrition any extras such as vitamins
     * 栄養素のビタミンなどのその他の栄養素を取得する
     *
     * @return array nutrition extras      栄養素のその他の情報
     */
    public function getExtras(): array
    {
        return $this->extras;
    }

    /**
     * Set nutrition description
     * 栄養素の説明を設定する     *
     *
     * @param string $description　nutrition description      栄養素の説明
     * @return void
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * Set nutrition extras array such as vitamins
     * 栄養素のビタミンなどのその他の栄養素を設定する
     *
     * @param array $extras　nutrition extras      栄養素のその他の情報
     * @return void
     */
    public function setExtras(array $extras): void
    {
        $this->extras = $extras;
    }


    /**
     * validate all unit inputs
     * すべての単位入力を検証する
     *
     * @param string $caloriesUnit  calories unit default: ignore  カロリー単位
     * @param string $weightUnit    weight unit default: ignore    重量単位
     * @return void
     *
     * @throws Exception           if units are invalid           単位が無効な場合
     */
    private function validateAllInputs(
        string $caloriesUnit = "ignore",
        string $weightUnit = "ignore"
    ): void { /*throws exception*/
        if ($caloriesUnit != "ignore" && !UnitConverter::validateUnit($caloriesUnit, "energy")) {
            throw new Exception("Invalid calories unit"); //カロリー単位が無効です
        }

        if ($caloriesUnit != "ignore" && !UnitConverter::validateUnit($weightUnit, "weight")) {
            throw new Exception("Invalid weight unit"); //重量単位が無効です
        }
    }
}
