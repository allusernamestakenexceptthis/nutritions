<?php

/**
 * This interface is used to define the methods that will be used in all Food classes
 * このインターフェースは、すべての食品クラスで使用されるメソッドを定義するために使用されます。
 *
 * @copyright  Copyright (C) Gomilkyway (https://gomilkyway.com)
 * @package    App\Models\Nutritions
 * @author     Adari ARi
 * @version    0.1.0
 * @license    MIT License (https://opensource.org/licenses/mit-license.php)
 */

declare(strict_types=1);

namespace App\Models\Food;

use App\Models\Nutritions\AbstractNutrition;

interface InterfaceFood
{
    public function addNutrition(AbstractNutrition $nutrition, mixed $nutritionWeight): void;

    public function setWeight(mixed $weight): void;

    public function getWeight(): float;

    public function getCalories(): float;

    public function getKCal(): int;

    public function getName(): string;

    public function setName(string $name): void;

    public function getNutritionalFacts(): array;
}
