<?php

namespace rocketfellows\SpecificCountryVatNumberFormatValidatorsConfig\tests\unit\mocks\configs;

use arslanimamutdinov\ISOStandard3166\Country;
use arslanimamutdinov\ISOStandard3166\ISO3166;
use rocketfellows\CountryVatFormatValidatorInterface\CountryVatFormatValidatorInterface;
use rocketfellows\SpecificCountryVatNumberFormatValidatorsConfig\SpecificCountryVatNumberFormatValidatorsConfig;
use rocketfellows\SpecificCountryVatNumberFormatValidatorsConfig\tests\unit\mocks\validators\RUVatFormatValidator;

class RUVatNumberFormatValidatorsConfig extends SpecificCountryVatNumberFormatValidatorsConfig
{
    public function getCountry(): Country
    {
        return ISO3166::RU();
    }

    protected function getDefaultValidator(): CountryVatFormatValidatorInterface
    {
        return new RUVatFormatValidator();
    }
}
