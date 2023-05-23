<?php
declare(strict_types=1);

namespace App\Models\Nutritions;

/**
 * This interface is used to define the methods that will be used in all Nutrition classes
 *
 * @copyright  Copyright (C) Gomilkyway (https://gomilkyway.com)
 * @package    App\Models\Nutritions
 * @author     Adari ARi
 * @version    0.1.0
 * @license    MIT License (https://opensource.org/licenses/mit-license.php)
 */

interface InterfaceNutrition
{
    public function setCaloriesPerMg(int $calories): void;

    public function getCaloriesPerMg(): int;

    public function getName(): string;

    public function setName(string $name): void;

    public function getEnergy(float $weight, string $in, string $per): mixed;

    public function setEnergy(int $calories, string $in, string $per): mixed;

    public function converter(mixed $value, string $from, string $to): mixed;
}
