<?php
use App\Testing\AbstractTestCase;
use App\Models\Food\Food;
use App\Models\Food\Meal;
use App\Models\Nutritions\AbstractNutrition;
use App\Models\Nutritions\Nutrition;
use App\Models\Nutritions\Carbohydrate;
use App\Models\Nutritions\Fat;
use App\Models\Nutritions\Protein;

final class FoodTest extends AbstractTestCase
{

    public function testFood() {
        $this->setAndMeasureMethod(new Carbohydrate(), 2.5);
        $this->setAndMeasureMethod(new Protein(), 2.5);
        $this->setAndMeasureMethod(new Fat(), 2.5);
    }

    public function testMeal() {
        $rice = new Food("rice", "100g");


        /*
            4 * 2.5 = 10
            9 * 0.3 = 2.7
            4 * 37.1 = 148.4
            ----------------
            161.1
        */
        $expectedRiceKCalTotal = 161; //kcal rounded
        $rice->addNutrition($this->getNewNutrition("Protein", 4), "2.5g", "100g");
        $rice->addNutrition($this->getNewNutrition("Fat", 9), "0.3g", "100g");
        $rice->addNutrition($this->getNewNutrition("Carbs", 4), "37.1g", "100g");

        $this->assertEquals($rice->getKCal(), $expectedRiceKCalTotal);

        /*
            4 * 16.5 = 66
            9 * 10.0 = 90
            4 * 12.1 = 48.4
            ----------------
            204.4
        */
        $expectedNattoKCalTotal = 204; //kcal rounded
        $natto = new Food("natto", "100g");
        $natto->addNutrition($this->getNewNutrition("Protein", 4), "16.5g", "100g");
        $natto->addNutrition($this->getNewNutrition("Fat", 9), "10.0g", "100g");
        $natto->addNutrition($this->getNewNutrition("Carbs", 4), "12.1g", "100g");
        $this->assertEquals($natto->getKCal(), $expectedNattoKCalTotal);

        $meal = new Meal("natto rice");
        $meal->addFood($rice);
        $meal->addFood($natto);

        /*
            161.1 + 204.4 = rounded = 366
        */
        $expectedTotal = 366;
        $this->assertEquals($meal->getKCal(), $expectedTotal);
    }

    private function getNewNutrition(string $name, int $energy): AbstractNutrition {
        return new Nutrition($name, $energy, $name, "kcal", "g");
    }

    private function setAndMeasureMethod(AbstractNutrition $nutrition, float $weight) : void {
        //kcal per g = calories per mg * 1000 / 1000
        $expectedKcal = (int)round((float)bcmul((string)$nutrition->getCaloriesPerMg(), (string)$weight, 10));
        $rice = new Food("rice", "100g");
        $rice->addNutrition($nutrition, $weight."g", "100g");
        $this->assertEquals($rice->getKCal(), $expectedKcal,
            "Calories: {$nutrition->getCaloriesPerMg()}, Weight: {$weight} "
        );
    }
}
