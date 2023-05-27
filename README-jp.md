<p align="center">
  <img src="https://github.com/allusernamestakenexceptthis/nutritions/actions/workflows/build.yml/badge.svg">
</p>

[ENGLISH](README.md)

# 栄養成分

コンポジットデザインの使用を示す栄養アプリです。あなたは食品から食事を、栄養から食品を作ることができます。

PHP 8.1以上が必要です。

# インストール

最も簡単なインストール方法は、Dockerを使用することです。

ルートディレクトリで：

```
docker-compose build
docker-compose up
```

すべてが順調に進めば、次のサイトにアクセスできます：

```
localhost:8731/index.php
```

Windows Homeの場合のように、WSL2を通じてDockerを実行する場合は、ファイルをWSL2コンテナ内に配置することを確認してください。

ユニットテストを実行します：

コンテナ内のbashにアクセスします

```
docker exec -it nutrition-app bash
```

nutrition-appはDockerコンテナの名前です。存在しないと表示される場合は、docker-compose upの出力を確認してください。アプリの名前は、アタッチメントの後に最初に表示されるべきです。

その形：
```
Attaching to nutrition-app, nutrition-web
```

/var/www/html/ 内でphpunitを実行します

```
phpunit
```

# Usage

例 #1:

```
use App\Models\Food\Food;
use App\Models\Food\Meal;
use App\Models\Nutritions\Carbohydrate;
use App\Models\Nutritions\Fat;
use App\Models\Nutritions\Protein;

use App\Utils\Formatter;
use App\Utils\UnitConverter;

//ご飯を作成します
$rice = new Food("Rice", 100);
$rice->addNutrition(new Protein(), "2.5g");
$rice->addNutrition(new Fat(), "0.3g");
$rice->addNutrition(new Carbohydrate(), "37.1g");

//納豆食品を作成します
$natto = new Food("Natto", 100);
$natto->addNutrition(new Protein(), "16.5g");
$natto->addNutrition(new Fat(), "10.0g");
$natto->addNutrition(new Carbohydrate(), "12.1g");

//2つの食品を組み合わせて食事を作成します
$meal = new Meal("Rice and Natto (納豆ご飯) ");
$meal->addFood($rice);
$meal->addFood($natto);

//フォーマットされた栄養成分表を取得します
$formattedTable = Formatter::nutritionFactToHtml($meal->getNutritionalFacts());
```

炭水化物、タンパク質、脂肪はすでに作成されていますが、任意の栄養を作成することができます：

```
use App\Models\Nutritions\Nutrition

$myAweSomeNutrition = new Nutrition("Natrium", 0.2);

```

任意の食品を作成し、食品に栄養を追加して食事を作成することができます

kcal、cal、グラム、mg、大量の食事がある場合はkgにまで単位を変換することができます

栄養成分表を取得し、HTMLでフォーマットすることができます

# バージョン
0.1.0
バグが発生する可能性があります。より多くのテストが必要です
