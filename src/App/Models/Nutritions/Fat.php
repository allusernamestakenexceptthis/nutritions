<?php
declare(strict_types=1);

namespace App\Models\Nutritions;

/**
 * Fat class
 *
 * @copyright  Copyright (C) Gomilkyway (https://gomilkyway.com)
 * @package    App\Models\Nutritions
 * @author     Adari ARi
 * @version    0.1.0
 * @license    MIT License (https://opensource.org/licenses/mit-license.php)
 */

class Fat extends AbstractNutrition
{
    public function __construct()
    {
        $kcal = 9;

        parent::__construct("Fat", $kcal, "F", "kcal", "g");
    }
}
