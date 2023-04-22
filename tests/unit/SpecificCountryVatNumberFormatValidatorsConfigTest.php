<?php

namespace rocketfellows\SpecificCountryVatNumberFormatValidatorsConfig\tests\unit;

use PHPUnit\Framework\TestCase;
use rocketfellows\CountryVatNumberFormatValidatorsConfig\CountryVatNumberFormatValidatorsConfigInterface;
use rocketfellows\SpecificCountryVatNumberFormatValidatorsConfig\tests\unit\mocks\configs\RUVatNumberFormatValidatorsConfig;

class SpecificCountryVatNumberFormatValidatorsConfigTest extends TestCase
{
    private const EXPECTED_INTERFACE_IMPLEMENTATION = CountryVatNumberFormatValidatorsConfigInterface::class;

    public function testSpecificConfigImplementedInterface(): void
    {
        $this->assertEquals(self::EXPECTED_INTERFACE_IMPLEMENTATION, (new RUVatNumberFormatValidatorsConfig()));
    }
}
