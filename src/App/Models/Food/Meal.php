<?php
declare(strict_types=1);

namespace App\Models\Food;

/**
 * Meal class acts as a food builder, a container for multiple foods
 *
 * @copyright  Copyright (C) Gomilkyway (https://gomilkyway.com)
 * @package    App\Models\Nutritions
 * @author     Adari ARi
 * @version    0.1.0
 * @license    MIT License (https://opensource.org/licenses/mit-license.php)
 */

use App\Utils\UnitConverter;

class Meal
{
    // foods list
    private array $foods = [];

    // meal name
    private string $name = "";

    // total calories
    protected float $calories = 0;

    // total weight
    protected float $weight = 0;

    /**
     * Meal constructor
     *
     * @param [type] $name  meal name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * Add food to the meal
     *
     * @param AbstractFood $food  food object
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
     *
     * @param array $food  food array containing model (Food)
     * @return void
     */
    public function addToCalculation(array &$food): void
    {
        $food['energyInCPerMg'] = $food['model']->getCalories();
        $this->calories += $food['energyInCPerMg'];
    }

    /**
     * Get the total calories of the meal (cal)
     *
     * @return float
     */
    public function getCalories(): float
    {
        return $this->calories;
    }

    /**
     * Get the total Calories of the meal (kcal)
     *
     * @return int
     */
    public function getKCal(): int
    {
        return (int)round(UnitConverter::convert($this->getCalories(), "cal", "kcal"));
    }

    /**
     * Get the nutritional facts of the meal
     *
     * @return array
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
     *
     * @return array
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
     *
     * @return float
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set the name of the meal
     *
     * @param string $name  meal name
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Get the weight of the meal
     *
     * @return float
     */
    public function getWeight(): float
    {
        return $this->weight;
    }

    /**
     * Merge and sum the nutritional facts of the different foods
     *
     * @param array $data  nutritional facts
     * @param array $nutritions  nutritional facts
     * @return array
     */
    private function mergeAndSum($data, $nutritions) : array
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
