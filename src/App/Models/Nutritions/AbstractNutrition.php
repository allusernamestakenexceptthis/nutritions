<?php
declare(strict_types=1);

namespace App\Models\Nutritions;

/**
 * Abstract class acts as parent that holds common methods, input and output
 *
 * @copyright  Copyright (C) Gomilkyway (https://gomilkyway.com)
 * @package    App\Models\Nutritions
 * @author     Adari ARi
 * @version    0.1.0
 * @license    MIT License (https://opensource.org/licenses/mit-license.php)
 */

use App\Errors\Exception;
use App\Utils\UnitConverter;

abstract class AbstractNutrition implements InterfaceNutrition
{
    //calories per mg
    protected int $caloriesPerMg = 0;

    //nutrition name such as Carbs
    protected string $name = "";

    //nutrition code such as C
    protected string $code = "";

    //nutrition description
    protected string $description = "";

    //nutrition weight
    protected array $extras = [];


    /**
     * AbstractNutrition constructor.
     *
     * @param string $name          nutrition name
     * @param mixed $energy         nutrition energy
     * @param string $code          nutrition code
     * @param string $eneryUnit     nutrition energy unit
     * @param string $weightUnit    nutrition weight unit
     */
    public function __construct(string $name, mixed $energy, string $code = "", string $eneryUnit = "kcal", string $weightUnit = "g")
    {
        if (!$code) {
            $code = $name;
        }

        $this->name = $name;
        $this->code = $code;
        $this->setEnergy($energy, $eneryUnit, $weightUnit);
    }

    /**
     * Set Calories per mg
     *
     * @param integer $calories
     * @return void
     */
    public function setCaloriesPerMg(int $calories): void
    {
        $this->caloriesPerMg = $calories;
    }

    /**
     * Get Calories per mg
     *
     * @return integer
     */
    public function getCaloriesPerMg(): int
    {
        return $this->caloriesPerMg;
    }

    /**
     * get nutrition energy in desired unit per weight unit
     *
     * @param float $weight         weight value e.g. 2.5
     * @param string $caloriesIn    calories unit default: kcal
     * @param string $per           weight unit default: g
     * @return void
     * @throws Exception            if units are invalid
     */
    public function getEnergy(float $weight, string $caloriesIn = "kcal", string $per = "g"): mixed
    {
        //throws exception if units are invalid
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
     *
     * @param integer $calories     calories value e.g. 5
     * @param string $in            calories unit default: kcal
     * @param string $per           weight unit default: g
     * @return mixed energy in desired unit per weight unit
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
     *
     * @param mixed $value      value to convert
     * @param string $from      unit to convert from
     * @param string $to        unit to convert to
     * @return mixed            converted value
     */
    public function converter(mixed $value, string $from, string $to): mixed /*throws exception*/
    {
        //round to 2 decimal places
        if ($to == "g") {
            $value = round((float)$value, 2);
        }

        return UnitConverter::convert($value, $from, $to);
    }

    /**
     * Get nutrition name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set nutrition name
     *
     * @param string $name
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Get nutrition code
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * Get nutrition description
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Get nutrition any extras such as vitamins
     *
     * @return array
     */
    public function getExtras(): array
    {
        return $this->extras;
    }

    /**
     * Set nutrition description
     *
     * @param string $description
     * @return void
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * Set nutrition extras array such as vitamins
     *
     * @param array $extras
     * @return void
     */
    public function setExtras(array $extras): void
    {
        $this->extras = $extras;
    }


    /**
     * validate all unit inputs
     *
     * @param string $caloriesUnit  calories unit
     * @param string $weightUnit    weight unit
     * @return void
     * @throws Exception
     */
    private function validateAllInputs(string $caloriesUnit = "ignore", string $weightUnit = "ignore"): void /* throws exception */
    {
        if ($caloriesUnit != "ignore" && !UnitConverter::validateUnit($caloriesUnit, "energy")) {
            throw new Exception("Invalid calories unit");
        }

        if ($caloriesUnit != "ignore" && !UnitConverter::validateUnit($weightUnit, "weight")) {
            throw new Exception("Invalid weight unit");
        }
    }


}
