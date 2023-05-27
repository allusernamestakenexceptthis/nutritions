<?php

/**
 * Meal class acts as a food builder, a container for multiple foods
 * ミールクラスは、複数の食品を格納するフードビルダー、コンテナとして機能します。
 *
 * @copyright  Copyright (C) Gomilkyway (https://gomilkyway.com)
 * @package    App\Models\Nutritions
 * @author     Adari ARi
 * @version    0.1.0
 * @license    MIT License (https://opensource.org/licenses/mit-license.php)
 */

declare(strict_types=1);

namespace App\Models\Food;

use App\Utils\UnitConverter;

class Meal
{
    // foods list
    // 食品リスト
    private array $foods = [];

    // meal name
    // 食事の名前
    private string $name = "";

    // total calories
    // 総カロリー
    protected float $calories = 0;

    // total weight
    // 総重量
    protected float $weight = 0;

    /**
     * Meal constructor
     * ミールコンストラクタ
     *
     * @param [type] $name  meal name  食事の名前
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * Add food to the meal
     * 食事に食品を追加する
     *
     * @param AbstractFood $food  food object  食品オブジェクト
     * @return void
     */
    public function addFood(AbstractFood $food): void
    {

        $food = [
            'model' => $food,
            'energyInCPerMg' => 0
        ];

        $this->weight += $food['model']->getWeight();
        $this->addToCalculation($food);

        $this->foods[] = $food;
    }

    /**
     * Add food to the calculation
     * 計算に食品を追加する
     *
     * @param array $food  food array containing model (Food) and energyInCPerMg
     *              モデル（食品）とenergyInCPerMgを含む食品配列
     * @return void
     */
    public function addToCalculation(array &$food): void
    {
        $food['energyInCPerMg'] = $food['model']->getCalories();
        $this->calories += $food['energyInCPerMg'];
    }

    /**
     * Get the total calories of the meal (cal)
     * 食事の総カロリーを取得する（cal）
     *
     * @return float total calories  総カロリー
     */
    public function getCalories(): float
    {
        return $this->calories;
    }

    /**
     * Get the total Calories of the meal (kcal)
     * 食事の総カロリーを取得する（kcal）
     *
     * @return int total Calories  総カロリー
     */
    public function getKCal(): int
    {
        return (int)round(UnitConverter::convert($this->getCalories(), "cal", "kcal"));
    }

    /**
     * Get the nutritional facts of the meal
     * 食事の栄養成分を取得する
     *
     * @return array nutritional facts  栄養成分
     */
    public function getNutritionalFacts($energyUnit = "kcal", $weightUnit = "g"): array
    {
        $foods = array();
        foreach ($this->foods as $food) {
            $nutrition = $food['model']->getNutritionalFacts($energyUnit, $weightUnit);
            $foods = $this->mergeAndSum($nutrition, $foods);
        }
        return $foods;
    }

    /**
     * Get the nutritional facts of the meal per food
     * 食事の栄養成分を食品ごとに取得する
     *
     * @return array nutritional facts  栄養成分
     */
    public function getFactsPerFood($energyUnit = "kcal", $weightUnit = "g"): array
    {
        $foods = array();
        foreach ($this->foods as $food) {
            $foods[$food['model']->getName()] = $food['model']->getNutritionalFacts($energyUnit, $weightUnit);
        }
        return $foods;
    }

    /**
     * Get the name of the meal
     * 食事の名前を取得する
     *
     * @return float
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set the name of the meal
     * 食事の名前を設定する
     *
     * @param string $name  meal name 食事の名前
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Get the weight of the meal
     * 食事の重量を取得する
     *
     * @return float weight  重量
     */
    public function getWeight(): float
    {
        return $this->weight;
    }

    /**
     * Merge and sum the nutritional facts of the different foods
     * 異なる食品の栄養成分をマージして合計する
     *
     * @param array $data  nutritional facts       栄養成分
     * @param array $nutritions  nutritional facts 栄養成分
     * @return array
     */
    private function mergeAndSum($data, $nutritions): array
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                if (!isset($nutritions[$key])) {
                    $nutritions[$key] = [];
                }
                $nutritions[$key] = $this->mergeAndSum($value, $nutritions[$key]);
            } else {
                if (array_key_exists($key, $nutritions)) {
                    if (is_numeric($value)) {
                        $nutritions[$key] += $value;
                    }
                } else {
                    $nutritions[$key] = $value;
                }
            }
        }

        return $nutritions;
    }
}
