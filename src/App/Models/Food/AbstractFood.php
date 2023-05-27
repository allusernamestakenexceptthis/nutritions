<?php

/**
 * Abstract class acts as parent that holds common methods, input and output
 * アブストラクトクラスは、テスト用の共通メソッド、入力、出力を保持する親クラス
 *
 * @copyright  Copyright (C) Gomilkyway (https://gomilkyway.com)
 * @package    App\Models\Food
 * @author     Adari ARi
 * @version    0.1.0
 * @license    MIT License (https://opensource.org/licenses/mit-license.php)
 */

declare(strict_types=1);

namespace App\Models\Food;

use App\Models\Nutritions\AbstractNutrition;
use App\Utils\UnitConverter;
use InvalidArgumentException;

abstract class AbstractFood implements InterfaceFood
{
    //holds the food name
    //食品名を保持
    protected string $name = "";

    //holds the food weight
    //食品の重さを保持
    protected float $foodWeight = 0;

    //holds the food weight unit
    //食品の重量単位を保持
    protected float $calories = 0;

    //holds the food nutritions
    //食品の栄養素を保持
    protected array $nutritions = [];

    /**
     * AbstractFood constructor.
     * AbstractFood コンストラクタ
     *
     * @param string $name    food name    食品名
     * @param mixed  $weight  food weight  食品の重さ
     */
    public function __construct(string $name, mixed $weight)
    {
        $this->name = $name;
        $this->setWeight($weight);
    }

    /**
     * Add nutrition to the food
     * 食品に栄養素を追加
     *
     * @param AbstractNutrition $nutrition          Nutrition object              栄養素オブジェクト
     * @param mixed             $nutritionWeight    Nutrition weight
     *                                              format number + unit e.g. 1g  数値+単位の形式
     * @param mixed             $each               For each X weight e.g. 100g   100gなどの重量ごと
     * @return void
     */
    public function addNutrition(AbstractNutrition $nutrition, mixed $nutritionWeight, mixed $each = 100): void
    {
        list($weight, $unit) = UnitConverter::getValueWithUnit($nutritionWeight, "g");
        list($eachValue, $eachUnit) = UnitConverter::getValueWithUnit($each, "g");


        $nutrition = [
            'model' => $nutrition,
            'weight' => $weight,
            'weight_unit' => $unit,
            'each' => $eachValue,
            'each_unit' => $eachUnit,
            'energyInCPerMg' => 0
        ];

        $this->addToCalculation($nutrition);
        $this->nutritions[] = $nutrition;
    }

    /**
     * Recalculate the calories and return the value
     * カロリーを再計算して値を返す
     *
     * @return float energy in calories per mg  エネルギー（カロリー/ mg）
     */
    public function recalculate(): float
    {
        $this->calories = 0;
        foreach ($this->nutritions as $nutrition) {
            $this->addToCalculation($nutrition);
        }
        return $this->calories;
    }

    /**
     * Add to the calories calculations
     * カロリー計算に追加
     *
     * @param array $nutrition holds nutrition information:
     *              model (nutrition object), weight, weight unit, each, each unit
     *              栄養情報を保持する配列
     *              モデル（栄養素オブジェクト）、重量、重量単位、各単位
     * @return void
     */
    public function addToCalculation(array &$nutrition): void
    {
        //convert each to mg
        $eachInMg = (float)bcdiv(
            (string)$this->foodWeight,
            (string)UnitConverter::convert(
                $nutrition['each'],
                $nutrition['each_unit'],
                "mg"
            )
        );

        $weightInMg = (float)bcmul(
            (string)UnitConverter::convert(
                $nutrition['weight'],
                $nutrition['weight_unit'],
                "mg"
            ),
            (string)$eachInMg,
            10
        );

        $nutrition['energyInCPerMg'] = ($nutrition['model']->getEnergy($weightInMg, "cal", "mg"));
        $this->calories += $nutrition['energyInCPerMg'];
    }

    /**
     * Set the weight of the food
     * 食品の重さを設定
     *
     * @param mixed $weight weight format number + unit e.g. 1g  数値+単位の形式
     * @return void
     */
    public function setWeight(mixed $weight): void
    {
        list($weight, $unit) = UnitConverter::getValueWithUnit($weight, "g");
        $weightInMg = UnitConverter::convert($weight, $unit, "mg");
        $this->foodWeight = $weightInMg;
    }

    /**
     * Get the food name
     * 食品名を取得
     *
     * @return string food name 食品名
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set the food name
     * 食品名を設定
     *
     * @param string $name food name 食品名
     * @return void
     */
    public function setName(string $name): void
    {
        if (empty($name)) {
            //throw exception if name is empty 食品名が空の場合は例外をスロー
            throw new InvalidArgumentException("Food name cannot be empty");
        }
        $this->name = $name;
    }

    /**
     * Get the food Calories (kcal) prt gram
     * 食品のカロリー（kcal）を取得
     *
     * @return integer food calories 食品のカロリー
     */
    public function getKCal(): int
    {
        $kcalories = UnitConverter::convert($this->calories, "cal", "kcal");

        return (int)round($kcalories);
    }

    /**
     * Get the food Calories (cal)
     * 食品のカロリー（cal）を取得
     *
     * @return float food calories 食品のカロリー
     */
    public function getCalories(): float
    {
        return $this->calories;
    }

    /**
     * Get the food weight
     * 食品の重さを取得
     *
     * @return float food weight 食品の重さ
     */
    public function getWeight(): float
    {
        return $this->foodWeight;
    }

    /**
     * Get the food nutritional facts
     * 食品の栄養成分を取得
     *
     * @return array food nutritional facts 食品の栄養成分
     */
    public function getNutritionalFacts($energyUnit = "kcal", $weightUnit = "g"): array
    {
        $nutritionalFacts = [];
        $totalEnergy = 0;
        $totalSingleEnergy = 0;
        $foodWeightInDesiredUnit = UnitConverter::convert($this->foodWeight, "mg", $weightUnit);
        foreach ($this->nutritions as $nutrition) {
            $model = $nutrition['model'];
            $key = $name = $model->getName();

            if (!isset($nutritionalFacts[$key])) {
                $nutritionalFacts[$key] = [
                    'label' => $name,
                    'code' => $model->getCode(),
                    'description' => $model->getDescription(),
                    'extra' => $model->getExtras(),
                    'singleEnergy' => 0,
                    'singleWeight' => 0,
                    'energy' => 0,
                    'weight' => 0,
                ];
            }

            $energyInDesiredUnit = UnitConverter::convert($nutrition['energyInCPerMg'], "cal", $energyUnit);
            $weightInDesiredUnit = UnitConverter::convert($nutrition['weight'], $nutrition['weight_unit'], $weightUnit);

            $energyInDesiredUnitSingle = (float)bcdiv(
                (string)$energyInDesiredUnit,
                (string)$foodWeightInDesiredUnit,
                10
            );
            $totalSingleEnergy += $energyInDesiredUnitSingle;
            $totalEnergy += $energyInDesiredUnit;

            $nutritionalFacts[$key]['singleEnergy'] += $energyInDesiredUnit;
            $nutritionalFacts[$key]['singleWeight'] += $weightInDesiredUnit;

            $nutritionalFacts[$key]['energy'] += $energyInDesiredUnit;
            $nutritionalFacts[$key]['weight'] += $weightInDesiredUnit;
        }
        return [
            'facts' => $nutritionalFacts,
            'totals' => [
                'energy_per_unit' => $totalSingleEnergy,
                'energy' => $totalEnergy,
            ],
        ];
    }
}
