<?php

/**
 * Carbohydrate class
 * 炭水化物　クラス
 *
 * @copyright  Copyright (C) Gomilkyway (https://gomilkyway.com)
 * @package    App\Models\Nutritions
 * @author     Adari ARi
 * @version    0.1.0
 * @license    MIT License (https://opensource.org/licenses/mit-license.php)
 */

declare(strict_types=1);

namespace App\Models\Nutritions;

class Carbohydrate extends AbstractNutrition
{
    public function __construct()
    {
        //set the kcal per gram //kcalをグラムごとに設定する
        $kcal = 4;

        parent::__construct("Carbohydrates", $kcal, "C", "kcal", "g");
    }
}
