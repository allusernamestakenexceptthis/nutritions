<?php

use App\Models\Food\Food;
use App\Models\Food\Meal;
use App\Models\Nutritions\Carbohydrate;
use App\Models\Nutritions\Fat;
use App\Models\Nutritions\Protein;
use App\Utils\Formatter;
use App\Utils\UnitConverter;

$rice = new Food("Rice", 100);
$rice->addNutrition(new Protein(), "2.5g");
$rice->addNutrition(new Fat(), "0.3g");
$rice->addNutrition(new Carbohydrate(), "37.1g");

$natto = new Food("Natto", 100);
$natto->addNutrition(new Protein(), "16.5g");
$natto->addNutrition(new Fat(), "10.0g");
$natto->addNutrition(new Carbohydrate(), "12.1g");


$meal = new Meal("Rice and Natto (納豆ご飯) ");
$meal->addFood($rice);
$meal->addFood($natto);

$formattedTable = Formatter::nutritionFactToHtml($meal->getNutritionalFacts());


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Meal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>
<body>
<div class="container">
    <h1>Meal: <?php echo $meal->getName();?></h1>
    <p>Weight: <?php echo UnitConverter::convert($meal->getWeight(), "mg", "g");?>g</p>
    <?php echo $formattedTable;?>
</div>
</body>
</html>
