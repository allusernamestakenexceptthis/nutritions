<?php

/**
 * Utility for formatting
 * フォーマットのためのユーティリティ
 *
 * @copyright  Copyright (C) Gomilkyway (https://gomilkyway.com)
 * @package    App\Models\Nutritions
 * @author     Adari ARi
 * @version    0.1.0
 * @license    MIT License (https://opensource.org/licenses/mit-license.php)
 */

declare(strict_types=1);

namespace App\Utils;

class Formatter
{
    /**
     * Convert nutrition fact to html 栄養成分をhtmlに変換する
     *
     * @param [type] $nutritionFact nutrition fact 栄養成分
     * @return string html representation of nutrition fact 栄養成分のhtml表現
     */
    public static function nutritionFactToHtml($nutritionFact): string
    {
        $html = "";
        $html .= "<table class='table table-striped table-bordered'>";
        $html .= "<thead>";
        $html .= "<tr>";
        $html .= "<th>Nutrition</th>";
        $html .= "<th>Energy</th>";
        $html .= "<th>Weight</th>";
        $html .= "</tr>";
        $html .= "</thead>";
        $html .= "<tbody>";

        foreach ($nutritionFact['facts'] as $nutrition) {
            $html .= "<tr>";
            $html .= "<td>" . $nutrition['label'] . "</td>";
            $html .= "<td>" . round($nutrition['energy']) . " kcal</td>";
            $html .= "<td>" . round($nutrition['weight'], 1) . "g</td>";
            $html .= "</tr>";
        }

        //total
        $html .= "<tr>";
        $html .= "<td>Total</td>";
        $html .= "<td>" . round($nutritionFact['totals']['energy']) . " kcal</td>";
        $html .= "<td>" . round($nutritionFact['totals']['energy_per_unit'], 2) . "kcal per g</td>";
        $html .= "</tr>";

        $html .= "</tbody>";
        $html .= "</table>";

        return $html;
    }
}
