<?php
declare(strict_types=1);

namespace App\Models\Nutritions;

/**
 * This abstract class acts as parent that holds common methods, input and output
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
    protected int $caloriesPerMg = 0;
    protected string $name = "";
    protected string $code = "";
    protected string $description = "";
    protected array $extras = [];



    public function __construct(string $name, mixed $energy, string $code = "", string $eneryUnit = "kcal", string $weightUnit = "g")
    {
        if (!$code) {
            $code = $name;
        }

        $this->name = $name;
        $this->code = $code;
        $this->setEnergy($energy, $eneryUnit, $weightUnit);
    }

    public function setCaloriesPerMg(int $calories): void
    {
        $this->caloriesPerMg = $calories;
    }

    public function getCaloriesPerMg(): int
    {
        return $this->caloriesPerMg;
    }

    public function getEnergy(float $weight, string $caloriesIn = "kcal", string $per = "g"): int /*throws exception*/
    {
        //throws exception if units are invalid
        $this->validateAllInputs($caloriesIn, $per);

        $caloriesPerMg = $this->getCaloriesPerMg();
        $weightInMg = $this->converter($weight, $per, "mg");

        $energy = $caloriesPerMg * $weightInMg;

        return intval($this->converter($energy, "cal", $caloriesIn));
    }

    public function setEnergy(int $calories, string $in, string $per): int
    {
        $calories = $this->converter($calories, $in, "cal");
        $mg = $this->converter(1, $per, "mg");

        $this->setCaloriesPerMg(intval($calories / $mg));

        return $this->getCaloriesPerMg();
    }

    public function converter(mixed $value, string $from, string $to): mixed /*throws exception*/
    {
        return UnitConverter::convert($value, $from, $to);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getExtras(): array
    {
        return $this->extras;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function setExtras(array $extras): void
    {
        $this->extras = $extras;
    }


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
