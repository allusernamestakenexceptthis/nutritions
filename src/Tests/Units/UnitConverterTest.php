<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Testing\AbstractTestCase;
use App\Utils\UnitConverter;

final class UnitConverterTest extends AbstractTestCase
{
    public function testConversion()
    {
        $gram = UnitConverter::convert(1500, "mg", "g");
        $this->assertEquals($gram, 1.5);

        $kcal = UnitConverter::convert(1000, "cal", "kcal");
        $this->assertEquals($kcal, 1);

        $this->expectException(\App\Errors\Exception::class);
        UnitConverter::convert(1000, "cal", "g");


        list($value, $unit) = UnitConverter::getValueWithUnit("1.5g");
        $this->assertEquals($value, 1.5);
        $this->assertEquals($unit, "g");
    }
}
