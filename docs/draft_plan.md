# problem

- We have nutritions such as protein, fat, carbs.
- Each nutrition has weight and Calories (kcal) per weight (g)
- Weight (g) must be rounded to one decimal point
- Carlories (kcal) integer rounded

- we must be able to construct food based on the components and future components
- be able to get kcal of the construction

Let's use the builder design

interface NutritionInterface
     definitions for abstract nutrition

Abstract NutritionAbstract
     acts as parent that holds common, input and output

 all other nutritions extend abstract set name and kcal in calories value in case we need change that in future

Food 
   interaface FoodInterface

abstract
   Abstract FoodAbstract

  All food, they'll use nutritions as building blocks


$meal->addFood(Rice, "200g");
$meal->addFood(new food(
    ''
));
