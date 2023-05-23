<?php
declare(strict_types=1);

namespace App\Utils;

/**
 * Utility for formatting
 *
 * @copyright  Copyright (C) Gomilkyway (https://gomilkyway.com)
 * @package    App\Models\Nutritions
 * @author     Adari ARi
 * @version    0.1.0
 * @license    MIT License (https://opensource.org/licenses/mit-license.php)
 */


class Formatter
{

    /*rray (size=2)
  'facts' =>
    array (size=3)
      'Protein' =>
        array (size=8)
          'label' => string 'Protein' (length=7)
          'code' => string 'P' (length=1)
          'description' => string '' (length=0)
          'extra' =>
            array (size=0)
              ...
          'singleEnergy' => float 76
          'singleWeight' => float 19
          'energy' => float 76
          'weight' => float 1900
      'Fat' =>
        array (size=8)
          'label' => string 'Fat' (length=3)
          'code' => string 'F' (length=1)
          'description' => string '' (length=0)
          'extra' =>
            array (size=0)
              ...
          'singleEnergy' => float 92.7
          'singleWeight' => float 10.3
          'energy' => float 92.7
          'weight' => float 1030
      'Carbohydrates' =>
        array (size=8)
          'label' => string 'Carbohydrates' (length=13)
          'code' => string 'C' (length=1)
          'description' => string '' (length=0)
          'extra' =>
            array (size=0)
              ...
          'singleEnergy' => float 196.8
          'singleWeight' => float 49.2
          'energy' => float 196.8
          'weight' => float 4920
  'totals' =>
    array (size=2)
      'energy_per_unit' => float 3.655
      'energy' => float 365.5
    */

    public static function nutritionFactToHtml($nutritionFact)
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
            $html .= "<td>" . round($nutrition['weight'],1) . "g</td>";
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
