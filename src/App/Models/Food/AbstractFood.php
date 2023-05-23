<?php
declare(strict_types=1);

namespace App\Models\Food;

/**
 * Abstract class acts as parent that holds common methods, input and output
 *
 * @copyright  Copyright (C) Gomilkyway (https://gomilkyway.com)
 * @package    App\Models\Food
 * @author     Adari ARi
 * @version    0.1.0
 * @license    MIT License (https://opensource.org/licenses/mit-license.php)
 */

use App\Models\Nutritions\AbstractNutrition;
use App\Utils\UnitConverter;

abstract class AbstractFood implements InterfaceFood
{
    //holds the food name
    protected string $name = "";

    //holds the food weight
    protected float $foodWeight = 0;

    //holds the food weight unit
    protected float $calories = 0;

    //holds the food nutritions
    protected array $nutritions = [];

    /**
     * AbstractFood constructor.
     *
     * @param string $name    food name
     * @param mixed  $weight  food weight
     */
    public function __construct(string $name, mixed $weight)
    {
        $this->name = $name;
        $this->setWeight($weight);
    }

    /**
     * Add nutrition to the food
     *
     * @param AbstractNutrition $nutrition          Nutrition object
     * @param mixed             $nutritionWeight    Nutrition weight format number + unit e.g. 1g
     * @param mixed             $each               For each X weight e.g. 100g
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
     *
     * @return float energy in calories per mg
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
     *
     * @param array $nutrition holds nutrition information:
     *              model (nutrition object), weight, weight unit, each, each unit
     * @return void
     */
    public function addToCalculation(array &$nutrition): void
    {
        //convert each to mg
        $eachInMg = (float)bcdiv((string)$this->foodWeight, (string)UnitConverter::convert($nutrition['each'], $nutrition['each_unit'], "mg"));

        $weightInMg = (float)bcmul((string)UnitConverter::convert($nutrition['weight'], $nutrition['weight_unit'], "mg"), (string)$eachInMg, 10);

        $nutrition['energyInCPerMg'] = ($nutrition['model']->getEnergy($weightInMg, "cal", "mg"));
        $this->calories += $nutrition['energyInCPerMg'];
    }

    /**
     * Set the weight of the food
     *
     * @param mixed $weight
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
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set the food name
     *
     * @param string $name
     * @return void
     */
    public function setName(string $name): void
    {
        if (empty($name)) {
            throw new \InvalidArgumentException("Food name cannot be empty");
        }
        $this->name = $name;
    }

    /**
     * Get the food Calories (kcal) prt gram
     *
     * @return integer
     */
    public function getKCal(): int
    {
        $kcalories = UnitConverter::convert($this->calories, "cal", "kcal");

        return (int)round($kcalories);
    }

    /**
     * Get the food Calories (cal)
     *
     * @return float
     */
    public function getCalories(): float
    {
        return $this->calories;
    }

    /**
     * Get the food weight
     *
     * @return float
     */
    public function getWeight() : float
    {
        return $this->foodWeight;
    }

    /**
     * Get the food nutritional facts
     *
     * @return array
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

            $energyInDesiredUnitSingle = (float)bcdiv((string)$energyInDesiredUnit, (string)$foodWeightInDesiredUnit, 10);

            $totalSingleEnergy += $energyInDesiredUnitSingle;
            $totalEnergy += $energyInDesiredUnit;

            $nutritionalFacts[$key]['singleEnergy'] += $energyInDesiredUnit;
            $nutritionalFacts[$key]['singleWeight'] += $weightInDesiredUnit;

            $nutritionalFacts[$key]['energy'] += $energyInDesiredUnit;
            $nutritionalFacts[$key]['weight'] += $weightInDesiredUnit;

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
