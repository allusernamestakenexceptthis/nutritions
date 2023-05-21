<?php
declare(strict_types=1);

namespace Gomilkyway\Nutrition\Models\Food;

/**
 * This interface is used to define the methods that will be used in all Food classes
 *
 * @copyright  Copyright (C) Gomilkyway (https://gomilkyway.com)
 * @package    Gomilkyway\Nutrition\Models\Nutritions
 * @author     Adari ARi
 * @version    0.1.0
 * @license    MIT License (https://opensource.org/licenses/mit-license.php)
 */

use Gomilkyway\Nutrition\Utils\UnitConverter;

class Meal
{
    private array $foods = [];
    private string $name = "";

    protected float $calories = 0;

    public function __construct($name)
    {
        $this->name = $name;
    }
    public function addFood(AbstractFood $food): void
    {

        $food = [
            'model' => $food,
            'energyInCPerMg' => 0
        ];

        $this->addToCalculation($food);

        $this->foods[] = $food;

    }

    public function addToCalculation(array &$food): void
    {
        $food['energyInCPerMg'] = $food['model']->getCalories();
        $this->calories += $food['energyInCPerMg'];
    }

    public function getCalories(): float
    {
        return $this->calories;
    }

    public function getNutritionalFacts($energyUnit = "kcal", $weightUnit = "g"): array
    {
        $foods = array();
        foreach ($this->foods as $food) {
            $nutrition = $food['model']->getNutritionalFacts($energyUnit, $weightUnit);
            $foods = $this->mergeAndSum($nutrition, $foods);
        }
        return $foods;
    }

    public function getFactsPerFood($energyUnit = "kcal", $weightUnit = "g"): array
    {
        $foods = array();
        foreach ($this->foods as $food) {
            $foods[$food['model']->getName()] = $food['model']->getNutritionalFacts($energyUnit, $weightUnit);
        }
        return $foods;
    }

    public function getName(): string
    {
        return $this->name;
    }

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
