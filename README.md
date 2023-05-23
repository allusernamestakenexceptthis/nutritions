# Nutritions

This is a nutrition app with builder design

requires php 8.1 or later

# Installation

Easiest way to install is to use docker.

in root directory:

```
docker-compose build
docker-compose up
```

if all goes well, you can access the site on 

```
localhost:8731/index.php
```

If you run docker through wsl2 as the case with windows home, please make sure to place the files inside wsl2 container. 

run unit test:

access bash inside container
```
docker exec -it nutrition-app bash
```

nutrition-app is the name of the docker container, if it says it doesn't exist, check output of docker-compose up, the name of the app should be the first after attaching to

as:
```
Attaching to nutrition-app, nutrition-web
```

run phpunit inside /var/www/html/

```
phpunit
```

# Usage

Example #1:

```
use App\Models\Food\Food;
use App\Models\Food\Meal;
use App\Models\Nutritions\Carbohydrate;
use App\Models\Nutritions\Fat;
use App\Models\Nutritions\Protein;

use App\Utils\Formatter;
use App\Utils\UnitConverter;

//Create rice
$rice = new Food("Rice", 100);
$rice->addNutrition(new Protein(), "2.5g");
$rice->addNutrition(new Fat(), "0.3g");
$rice->addNutrition(new Carbohydrate(), "37.1g");

//Create natto food
$natto = new Food("Natto", 100);
$natto->addNutrition(new Protein(), "16.5g");
$natto->addNutrition(new Fat(), "10.0g");
$natto->addNutrition(new Carbohydrate(), "12.1g");


//Create meal by combining two food
$meal = new Meal("Rice and Natto (納豆ご飯) ");
$meal->addFood($rice);
$meal->addFood($natto);

//Get formatted nutritional fact
$formattedTable = Formatter::nutritionFactToHtml($meal->getNutritionalFacts());
```

Carbs, protein, and fat are pre-created, but you can create any nutrition you want:

```
use App\Models\Nutritions\Nutrition

$myAweSomeNutrition = new Nutrition("Natrium", 0.2);

```

You can create any food, and meal by adding nutritions to food, and adding food to meal

You can convert between units, kcal, cal, gram, mg, and even kg if there are big meal

You can get nutritional facts, and format it with html

# version
0.1.0
bugs are possible. needs more testing

# Thanks

Thank you for checking this
