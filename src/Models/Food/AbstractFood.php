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

use Gomilkyway\Nutrition\Models\Nutritions\AbstractNutrition;
use Gomilkyway\Nutrition\Utils\UnitConverter;

class AbstractFood implements InterfaceFood
{
    protected string $name = "";

    protected float $foodWeight = 0;
    protected float $calories = 0;
    protected array $nutritions = [];

    public function __construct(string $name, mixed $weight)
    {
        $this->name = $name;
        $this->setWeight($weight);
    }

    public function addNutrition(AbstractNutrition $nutrition, mixed $nutritionWeight, mixed $each = 1): void
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

    public function recalculate(): float
    {
        $this->calories = 0;
        foreach ($this->nutritions as $nutrition) {
            $this->addToCalculation($nutrition);
        }
        return $this->calories;
    }

    public function addToCalculation(array &$nutrition): void
    {
        //convert each to mg
        $inMg = UnitConverter::convert($nutrition['each'], $nutrition['each_unit'], "mg");

        $nutrition['energyInCPerMg'] = ($nutrition['model']->getEnergy($nutrition['weight'], "kcal", $nutrition['weight_unit']) / $inMg);
        $this->calories += $nutrition['energyInCPerMg'];
    }

    public function setWeight(mixed $weight): void
    {
        list($weight, $unit) = UnitConverter::getValueWithUnit($weight, "g");
        $weightInMg = UnitConverter::convert($weight, $unit, "mg");
        $this->foodWeight = $weightInMg;
    }

    public function converter(mixed $value, string $from, string $to): mixed
    {
        foreach ($this->nutritions as $nutrition) {
            $value = $nutrition->converter($value, $from, $to);
        }
        return $value;
    }

    public function getCalories()
    {
        return $this->calories * $this->foodWeight;
    }

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

            $totalSingleEnergy += $energyInDesiredUnit * $foodWeightInDesiredUnit;
            $totalEnergy += $energyInDesiredUnit;

            $nutritionalFacts[$key]['singleEnergy'] += $energyInDesiredUnit;
            $nutritionalFacts[$key]['singleWeight'] += $weightInDesiredUnit;

            $nutritionalFacts[$key]['energy'] += $energyInDesiredUnit * $foodWeightInDesiredUnit;
            $nutritionalFacts[$key]['weight'] += $weightInDesiredUnit * $foodWeightInDesiredUnit;

        }
        return [
            'facts'=>$nutritionalFacts,
            'totals'=>[
                'energy_per_unit' => $totalSingleEnergy,
                'energy' => $totalEnergy,
            ],
        ];
    }
}
