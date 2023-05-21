<?php

use Gomilkyway\Nutrition\Models\Food\AbstractFood;
use Gomilkyway\Nutrition\Models\Food\Meal;
use Gomilkyway\Nutrition\Models\Nutritions\Carbohydrate;
use Gomilkyway\Nutrition\Models\Nutritions\Fat;
use Gomilkyway\Nutrition\Models\Nutritions\Protein;


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
