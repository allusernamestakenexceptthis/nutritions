<?php


use App\Models\Food\AbstractFood;
use App\Models\Food\Meal;
use App\Models\Nutritions\Carbohydrate;
use App\Models\Nutritions\Fat;
use App\Models\Nutritions\Protein;


$rice = new AbstractFood("Rice", 100);
$rice->addNutrition(new Protein(), "2.5g");
$rice->addNutrition(new Fat(), "0.3g");
$rice->addNutrition(new Carbohydrate(), "37.1g");

$natto = new AbstractFood("Natto", 100);
$natto->addNutrition(new Protein(), "16.5g");
$natto->addNutrition(new Fat(), "10.0g");
$natto->addNutrition(new Carbohydrate(), "12.1g");


$meal = new Meal("Rice and Natto (納豆ご飯) ");
$meal->addFood($rice);
$meal->addFood($natto);
var_dump($meal->getNutritionalFacts());


//$chicken = new AbstractFood("Chicken", 100);
