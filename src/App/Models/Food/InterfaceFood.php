<?php
declare(strict_types=1);

namespace App\Models\Food;

/**
 * This interface is used to define the methods that will be used in all Food classes
 *
 * @copyright  Copyright (C) Gomilkyway (https://gomilkyway.com)
 * @package    App\Models\Nutritions
 * @author     Adari ARi
 * @version    0.1.0
 * @license    MIT License (https://opensource.org/licenses/mit-license.php)
 */

use App\Models\Nutritions\AbstractNutrition;

interface InterfaceFood
{

    public function addNutrition(AbstractNutrition $nutrition, mixed $nutritionWeight): void;

    public function setWeight(mixed $weight): void;

    public function converter(mixed $value, string $from, string $to): mixed;

    public function getNutritionalFacts(): array;

}
