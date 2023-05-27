<?php

/**
 * Fat class
 * 脂肪クラス
 *
 * @copyright  Copyright (C) Gomilkyway (https://gomilkyway.com)
 * @package    App\Models\Nutritions
 * @author     Adari ARi
 * @version    0.1.0
 * @license    MIT License (https://opensource.org/licenses/mit-license.php)
 */

declare(strict_types=1);

namespace App\Models\Nutritions;

class Fat extends AbstractNutrition
{
    public function __construct()
    {
        //set the kcal per gram //kcalをグラムごとに設定する
        $kcal = 9;

        parent::__construct("Fat", $kcal, "F", "kcal", "g");
    }
}
